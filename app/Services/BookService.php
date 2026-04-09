<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookService
{
    /**
     * Get filtered and paginated books.
     */
    public function getFilteredBooks(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Book::with('addedBy');
        
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        if (!empty($filters['available']) && $filters['available'] == '1') {
            $query->whereRaw('total_copies > (
                SELECT COUNT(*) FROM borrowings 
                WHERE borrowings.book_id = books.id 
                AND borrowings.status IN (\'pending\', \'approved\', \'borrowed\', \'pending_return\')
            )');
        }
        
        if (!empty($filters['added_by'])) {
            $query->whereHas('addedBy', function($q) use ($filters) {
                $q->where('role', $filters['added_by']);
            });
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Get book details with relationships.
     */
    public function getBookDetails(Book $book, ?int $userId = null): array
    {
        $book->load(['addedBy', 'borrowings.user', 'reviews.user', 'categories']);
        
        $data = [
            'book' => $book,
            'user_review' => null,
            'in_collection' => false,
            'has_approved_borrowing' => false,
            'average_rating' => $book->reviews()->avg('rating'),
            'total_reviews' => $book->reviews()->count(),
        ];
        
        if ($userId) {
            $data['user_review'] = $book->reviews()->where('user_id', $userId)->first();
            $data['in_collection'] = $book->collections()->where('user_id', $userId)->exists();
            $data['has_approved_borrowing'] = \App\Models\Borrowing::where('user_id', $userId)
                ->where('book_id', $book->id)
                ->whereIn('status', ['approved', 'borrowed'])
                ->exists();
        }
        
        return $data;
    }

    /**
     * Create a new book.
     */
    public function createBook(array $data, int $userId): Book
    {
        $bookData = $data;
        $bookData['available_copies'] = $data['total_copies'];
        $bookData['added_by'] = $userId;

        if (isset($data['cover_image'])) {
            $bookData['cover_image'] = $data['cover_image']->store('book-covers', 'public');
        }

        return Book::create($bookData);
    }

    /**
     * Update an existing book.
     */
    public function updateBook(Book $book, array $data): Book
    {
        $bookData = $data;

        if (isset($data['cover_image'])) {
            // Delete old cover image
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            
            $bookData['cover_image'] = $data['cover_image']->store('book-covers', 'public');
        }

        $book->update($bookData);

        return $book->fresh();
    }

    /**
     * Delete a book with validation.
     */
    public function deleteBook(Book $book): void
    {
        if ($book->activeBorrowings()->count() > 0) {
            throw new \Exception('Cannot delete book that is currently borrowed!');
        }

        // Delete cover image
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();
    }

    /**
     * Get all categories sorted by name.
     */
    public function getCategories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Get category names only.
     */
    public function getCategoryNames(): Collection
    {
        return Category::orderBy('name')->pluck('name');
    }

    /**
     * Check if book can be deleted.
     */
    public function canDeleteBook(Book $book): bool
    {
        return $book->activeBorrowings()->count() === 0;
    }

    /**
     * Get book statistics.
     */
    public function getBookStats(Book $book): array
    {
        return [
            'total_borrowings' => $book->borrowings()->count(),
            'active_borrowings' => $book->activeBorrowings()->count(),
            'total_reviews' => $book->reviews()->count(),
            'average_rating' => $book->reviews()->avg('rating'),
            'in_collections' => $book->collections()->count(),
        ];
    }
}
