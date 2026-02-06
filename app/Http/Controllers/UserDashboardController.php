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
        $roleRequests = $user->roleRequests()->latest()->get();
        
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
        
        return view('dashboards.user', compact('user', 'roleRequests', 'stats', 'recentBorrowings', 'recommendedBooks'));
    }

    public function requestRole(Request $request)
    {
        $request->validate([
            'requested_role' => 'required|in:petugas',
            'reason' => 'required|string|max:500',
        ]);

        $user = auth()->user();

        // Cek apakah sudah ada request pending
        $existingRequest = $user->roleRequests()
            ->where('status', 'pending')
            ->where('requested_role', $request->requested_role)
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Anda sudah memiliki permintaan role yang sedang diproses.');
        }

        RoleRequest::create([
            'user_id' => $user->id,
            'requested_role' => $request->requested_role,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Permintaan role berhasil dikirim dan menunggu persetujuan admin.');
    }
}
