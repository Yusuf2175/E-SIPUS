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
        
        // Recent borrowings with pagination
        $recentBorrowings = Borrowing::with(['book', 'user'])
            ->latest()
            ->paginate(6);
        
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
        
        // My active borrowings (for CTA)
        $myActiveBorrowings = Borrowing::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->count();
        
        return view('dashboards.petugas', compact('user', 'stats', 'recentBorrowings', 'overdueBorrowings', 'lowStockBooks', 'myActiveBorrowings'));
    }

    public function manageUsers()
    {
        // Only show regular users (not admin or petugas)
        $users = User::where('role', 'user')
            ->oldest()
            ->paginate(10);
        
        return view('petugas.users', compact('users'));
    }
}
