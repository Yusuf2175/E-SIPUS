<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Borrowing::with(['book', 'user', 'approvedBy']);
        
        // Filter by user role
        if ($user->isUser()) {
            // User hanya melihat peminjaman mereka sendiri yang tidak di-hide
            $query->where('user_id', $user->id)
                  ->where('hidden_by_user', false);
        } elseif ($user->isPetugas()) {
            // Petugas melihat peminjaman untuk buku yang mereka tambahkan
            // Tapi jika petugas sendiri yang meminjam, hide history mereka tidak tampil
            $query->where(function($q) use ($user) {
                $q->whereHas('book', function($bookQuery) use ($user) {
                    $bookQuery->where('added_by', $user->id);
                })->where(function($hideQuery) use ($user) {
                    // Jika peminjam adalah petugas itu sendiri dan di-hide, jangan tampilkan
                    $hideQuery->where('user_id', '!=', $user->id)
                              ->orWhere('hidden_by_user', false);
                });
            });
        }
        // Admin dapat melihat semua peminjaman (termasuk yang di-hide oleh user/petugas)
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->whereIn('status', ['approved', 'borrowed'])
                    ->where('due_date', '<', Carbon::now()->toDateString());
            } elseif ($request->status === 'borrowed') {
                // "Currently Borrowed" tab shows both approved and borrowed
                $query->whereIn('status', ['approved', 'borrowed']);
            } else {
                $query->where('status', $request->status);
            }
        }
        
        $borrowings = $query->orderBy('created_at', 'desc')->paginate(6);
        
        // Statistics for Admin/Petugas
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $statsQuery = Borrowing::query();
            
            // Untuk petugas, hanya hitung peminjaman buku yang mereka tambahkan
            if ($user->isPetugas()) {
                $statsQuery->whereHas('book', function($q) use ($user) {
                    $q->where('added_by', $user->id);
                });
            }
            
            $stats = [
                'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
                'approved' => (clone $statsQuery)->whereIn('status', ['approved', 'borrowed'])->count(),
                'active' => (clone $statsQuery)->whereIn('status', ['approved', 'borrowed'])->count(),
                'rejected' => (clone $statsQuery)->where('status', 'rejected')->count(),
                'pending_return' => (clone $statsQuery)->where('status', 'pending_return')->count(),
                'returned' => (clone $statsQuery)->where('status', 'returned')->count(),
                'overdue' => (clone $statsQuery)->whereIn('status', ['approved', 'borrowed'])
                    ->where('due_date', '<', Carbon::now()->toDateString())
                    ->count(),
                'total' => (clone $statsQuery)->count()
            ];
        }
        
        return view('borrowings.index', compact('borrowings', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $userId = $request->filled('user_id') && (Auth::user()->isAdmin() || Auth::user()->isPetugas()) 
            ? $request->user_id 
            : Auth::id();

        $book = Book::findOrFail($request->book_id);

        // Check if user already has pending or active borrowing for this book
        $existingBorrowing = Borrowing::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();

        if ($existingBorrowing) {
            if ($existingBorrowing->status === 'pending') {
                return redirect()->back()->with('error', 'You already have a pending borrowing request for this book!');
            }
            return redirect()->back()->with('error', 'You have already borrowed this book!');
        }

        // Check borrowing limit (max 3 books per user) - count pending, approved, and borrowed
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->count();

        if ($activeBorrowings >= 3) {
            return redirect()->back()->with('error', 'Borrowing limit reached (3 books maximum)!');
        }

        // Create borrowing request with pending status
        $borrowing = Borrowing::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'borrowed_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addDays(14)->toDateString(),
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Borrowing request submitted successfully! Please wait for admin/staff approval.');
    }

    // Method untuk admin/petugas menyetujui peminjaman
    public function approve(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can approve
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have permission to approve borrowing requests!');
        }

        // Petugas hanya bisa approve peminjaman untuk buku yang mereka tambahkan
        if ($user->isPetugas() && $borrowing->book->added_by !== $user->id) {
            return redirect()->back()->with('error', 'You can only approve borrowing requests for books you added!');
        }

        // Check if status is pending
        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'This borrowing request cannot be approved!');
        }

        $book = $borrowing->book;

        // Calculate actual available copies
        $activeBorrowingsCount = $book->activeBorrowings()->count();
        $actualAvailableCopies = $book->total_copies - $activeBorrowingsCount;

        // Check if book is still available
        if ($actualAvailableCopies <= 0) {
            return redirect()->back()->with('error', 'Book is not available! All copies are currently borrowed.');
        }

        // Update borrowing status to approved
        $borrowing->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now()
        ]);

        // Decrease available copies
        $book->decrement('available_copies');

        return redirect()->back()->with('success', 'Borrowing request approved successfully!');
    }

    // Method untuk admin/petugas menolak peminjaman
    public function reject(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can reject
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have permission to reject borrowing requests!');
        }

        // Petugas hanya bisa reject peminjaman untuk buku yang mereka tambahkan
        if ($user->isPetugas() && $borrowing->book->added_by !== $user->id) {
            return redirect()->back()->with('error', 'You can only reject borrowing requests for books you added!');
        }

        // Check if status is pending
        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'This borrowing request cannot be rejected!');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500'
        ], [
            'reject_reason.required' => 'Reject reason is required'
        ]);

        // Update borrowing status to rejected
        $borrowing->update([
            'status' => 'rejected',
            'reject_reason' => $request->reject_reason,
            'approved_by' => $user->id,
            'approved_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Borrowing request rejected successfully!');
    }

    public function return(Request $request, Borrowing $borrowing)
    {
        $currentUser = Auth::user();
        $borrower = $borrowing->user;
        
        // Check if book is already returned or pending return
        if (!in_array($borrowing->status, ['approved', 'borrowed'])) {
            return redirect()->back()->with('error', 'Book has already been returned or is pending return!');
        }
        
        // SEMUA USER (termasuk admin/petugas) request return dulu
        // Tidak ada yang bisa langsung return tanpa approval
        $borrowing->update([
            'status' => 'pending_return',
            'return_requested_at' => Carbon::now(),
            'return_reason' => $request->return_reason ?? 'normal',
            'return_notes' => $request->return_notes
        ]);

        return redirect()->back()->with('success', 'Return request submitted successfully! Please wait for approval.');
    }

    // Method untuk admin/petugas approve return request dari user
    public function approveReturn(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can approve return
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have permission to approve return requests!');
        }

        // Petugas hanya bisa approve return untuk buku yang mereka tambahkan
        if ($user->isPetugas() && $borrowing->book->added_by !== $user->id) {
            return redirect()->back()->with('error', 'You can only approve return requests for books you added!');
        }

        // Check if status is pending_return
        if ($borrowing->status !== 'pending_return') {
            return redirect()->back()->with('error', 'This return request cannot be approved!');
        }

        // Update borrowing status to returned
        $borrowing->update([
            'status' => 'returned',
            'returned_date' => Carbon::now()->toDateString(),
            'returned_by' => $user->id
        ]);

        // Increase available copies (except if book is lost)
        if ($borrowing->return_reason !== 'book_lost') {
            $borrowing->book->increment('available_copies');
        }

        return redirect()->back()->with('success', 'Return request approved successfully!');
    }

    public function returnPage()
    {
        $user = Auth::user();
        
        // Get active borrowings for the user (status approved = sedang dipinjam, pending_return = menunggu approval return)
        $activeBorrowings = Borrowing::with(['book'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed', 'pending_return']) // Include pending_return
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Calculate overdue status for each borrowing
        $activeBorrowings->each(function ($borrowing) {
            $borrowing->is_overdue = Carbon::parse($borrowing->due_date)->isPast();
            $borrowing->days_remaining = (int) Carbon::now()->diffInDays(Carbon::parse($borrowing->due_date), false);
        });
        
        return view('borrowings.return', compact('activeBorrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        // Check if user is authorized to view this borrowing
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas() && $borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['book', 'user', 'approvedBy']);
        
        return view('borrowings.show', compact('borrowing'));
    }

    // Hide borrowing history (soft delete for user)
    public function hideHistory(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // User, Petugas, dan Admin bisa hide history mereka sendiri
        if ($borrowing->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You are not authorized to hide this history!');
        }

        // Only returned borrowings can be hidden
        if ($borrowing->status !== 'returned') {
            return redirect()->back()->with('error', 'Only returned borrowings can be hidden!');
        }

        if ($borrowing->hideFromUser()) {
            return redirect()->back()->with('success', 'History deleted successfully!');
        }

        return redirect()->back()->with('error', 'Failed to hide history!');
    }

    // Unhide borrowing history (restore)
    public function unhideHistory(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // User, Petugas, dan Admin bisa unhide history mereka sendiri
        if ($borrowing->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You are not authorized to unhide this history!');
        }

        if ($borrowing->unhideFromUser()) {
            return redirect()->back()->with('success', 'History restored successfully!');
        }

        return redirect()->back()->with('error', 'Failed to restore history!');
    }

    // Permanent delete (Admin only)
    public function destroy(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin can permanently delete
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Only admin can permanently delete borrowing records!');
        }

        $bookTitle = $borrowing->book->title;
        $borrowing->delete();

        return redirect()->back()->with('success', "Borrowing record for '{$bookTitle}' has been permanently deleted!");
    }
}
