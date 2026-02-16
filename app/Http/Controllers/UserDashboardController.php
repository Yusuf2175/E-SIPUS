<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Collection;
use App\Models\Review;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();        
        // Statistics
        $stats = [
            'active_borrowings' => $user->borrowings()->where('status', 'borrowed')->count(),
            'total_borrowings' => $user->borrowings()->count(),
            'collections' => $user->collections()->count(),
            'reviews' => $user->reviews()->count(),
        ];
        
        // Recent borrowings
        $recentBorrowings = $user->borrowings()
            ->with('book')
            ->latest()
            ->take(5)
            ->get();
        
        // Recommended books (based on user's borrowing history)
        $recommendedBooks = Book::available()
            ->inRandomOrder()
            ->take(6)
            ->get();
        
        return view('dashboards.user', compact('user','stats', 'recentBorrowings', 'recommendedBooks'));
    }
}
