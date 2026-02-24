<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
        
        return view('landingPage', compact('recommendedBooks'));
    }
}

