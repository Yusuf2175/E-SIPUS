<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    /**
     * Get paginated categories with books count.
     */
    public function getPaginatedCategories(int $perPage = 15): LengthAwarePaginator
    {
        // Get all categories with books count using eager loading optimization
        $categories = Category::withCount(['books' => function ($query) {
            // If you're using relationship, this will be more efficient
            // Otherwise, we'll use the manual count below
        }])
        ->orderBy('name')
        ->get()
        ->map(function ($category) {
            // Count books where category field matches this category name
            $category->books_count = Book::where('category', $category->name)->count();
            return $category;
        });
        
        $currentPage = request()->get('page', 1);
        
        return new LengthAwarePaginator(
            $categories->forPage($currentPage, $perPage),
            $categories->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Create a new category.
     */
    public function createCategory(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null
        ]);

        return $category->fresh();
    }

    /**
     * Delete a category if it has no associated books.
     */
    public function deleteCategory(Category $category): bool
    {
        // Check if category has books
        $booksCount = Book::where('category', $category->name)->count();
        
        if ($booksCount > 0) {
            throw new \Exception('Cannot delete category with associated books! Please remove books from this category first.');
        }

        return $category->delete();
    }

    /**
     * Check if category has associated books.
     */
    public function hasBooks(Category $category): bool
    {
        return Book::where('category', $category->name)->exists();
    }

    /**
     * Get books count for a category.
     */
    public function getBooksCount(Category $category): int
    {
        return Book::where('category', $category->name)->count();
    }
}
