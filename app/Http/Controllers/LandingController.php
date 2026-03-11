<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        // Get 6 recommended books (latest or most reviewed)
        $recommendedBooks = Book::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Get categories with book count
        $categories = \App\Models\Category::withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->take(4)
            ->get();
        
        // Statistics for about section
        $totalUsers = User::whereIn('role', ['user', 'petugas'])->count();
        $totalBooks = Book::count();
        $totalBorrowings = Borrowing::count();
        
        return view('landingPage', compact('recommendedBooks', 'categories', 'totalUsers', 'totalBooks', 'totalBorrowings'));
    }
}

