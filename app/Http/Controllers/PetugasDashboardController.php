<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistics
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', Carbon::now()->toDateString())
                ->count(),
            'total_users' => User::where('role', 'user')->count(),
            'books_added_by_me' => Book::where('added_by', $user->id)->count(),
        ];
        
        // Recent borrowings
        $recentBorrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->take(10)
            ->get();
        
        // Overdue borrowings
        $overdueBorrowings = Borrowing::with(['book', 'user'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
        
        // Books with low stock
        $lowStockBooks = Book::where('available_copies', '<=', 2)
            ->where('available_copies', '>', 0)
            ->orderBy('available_copies', 'asc')
            ->take(5)
            ->get();
        
        return view('dashboards.petugas', compact('user', 'stats', 'recentBorrowings', 'overdueBorrowings', 'lowStockBooks'));
    }
}
