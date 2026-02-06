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
            $query->where('user_id', $user->id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now()->toDateString());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics for Admin/Petugas
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $stats = [
                'active' => Borrowing::where('status', 'borrowed')->count(),
                'returned' => Borrowing::where('status', 'returned')->count(),
                'overdue' => Borrowing::where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now()->toDateString())
                    ->count(),
                'total' => Borrowing::count()
            ];
        }
        
        return view('borrowings.index', compact('borrowings', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $book = Book::findOrFail($request->book_id);
        
        // Check if book is available
        if (!$book->isAvailable()) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam!');
        }
        
        // Check if user already borrowed this book and hasn't returned it
        $existingBorrowing = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->first();
            
        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini!');
        }
        
        // Check borrowing limit (max 3 books per user)
        $activeBorrowings = Borrowing::where('user_id', Auth::id())
            ->where('status', 'borrowed')
            ->count();
            
        if ($activeBorrowings >= 3) {
            return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku)!');
        }

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrowed_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addDays(14)->toDateString(), // 2 weeks borrowing period
            'status' => 'borrowed',
            'notes' => $request->notes,
            'approved_by' => Auth::id() // Auto-approve for now, can be changed later
        ]);

        // Decrease available copies
        $book->decrement('available_copies');

        return redirect()->back()->with('success', 'Buku berhasil dipinjam! Harap kembalikan sebelum tanggal ' . $borrowing->due_date->format('d/m/Y'));
    }

    public function return(Borrowing $borrowing)
    {
        // Check if user is authorized to return this book
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas() && $borrowing->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengembalikan buku ini!');
        }

        if ($borrowing->status !== 'borrowed') {
            return redirect()->back()->with('error', 'Buku sudah dikembalikan!');
        }

        // Update borrowing status
        $borrowing->update([
            'status' => 'returned',
            'returned_date' => Carbon::now()->toDateString()
        ]);

        // Increase available copies
        $borrowing->book->increment('available_copies');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
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
}