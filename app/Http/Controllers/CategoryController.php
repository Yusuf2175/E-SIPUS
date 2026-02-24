<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        // Only admin and petugas can access
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas()) {
            abort(403, 'Unauthorized access');
        }

        // Get categories and count books using the category string field
        $categories = Category::all()->map(function ($category) {
            // Count books where category field matches this category name
            $category->books_count = \App\Models\Book::where('category', $category->name)->count();
            return $category;
        })->sortBy('name');
        
        // Paginate manually
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $categories = new \Illuminate\Pagination\LengthAwarePaginator(
            $categories->forPage($currentPage, $perPage),
            $categories->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        // Only admin and petugas can create
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        // Only admin and petugas can update
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        // Only admin and petugas can delete
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        // Check if category has books using the category string field
        $booksCount = \App\Models\Book::where('category', $category->name)->count();
        
        if ($booksCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated books! Please remove books from this category first.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
