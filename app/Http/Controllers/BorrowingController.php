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

    public function return(Request $request, Borrowing $borrowing)
    {
        $currentUser = Auth::user();
        $borrower = $borrowing->user;
        
        // Check if book is already returned
        if ($borrowing->status !== 'borrowed') {
            return redirect()->back()->with('error', 'Buku sudah dikembalikan!');
        }
        
        // Authorization logic based on borrower's role
        if ($borrower->isAdmin()) {
            // If admin borrowed the book, only admin can return it
            if (!$currentUser->isAdmin()) {
                return redirect()->back()->with('error', 'Hanya Admin yang dapat mengembalikan buku yang dipinjam oleh Admin!');
            }
        } elseif ($borrower->isPetugas()) {
            // If petugas borrowed the book, only petugas can return it
            if (!$currentUser->isPetugas()) {
                return redirect()->back()->with('error', 'Hanya Petugas yang dapat mengembalikan buku yang dipinjam oleh Petugas!');
            }
        } else {
            // Regular user - the user themselves, admin, or petugas can return it
            if ($borrowing->user_id !== $currentUser->id && !$currentUser->isAdmin() && !$currentUser->isPetugas()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengembalikan buku ini!');
            }
        }

        // Validate return reason if returned by admin/petugas for user's book
        if (($currentUser->isAdmin() || $currentUser->isPetugas()) && $borrower->isUser()) {
            $request->validate([
                'return_reason' => 'required|in:normal,user_missing,book_damaged,book_lost',
                'return_notes' => 'nullable|string|max:500'
            ], [
                'return_reason.required' => 'Alasan pengembalian harus dipilih',
                'return_reason.in' => 'Alasan pengembalian tidak valid'
            ]);
        }

        // Update borrowing status
        $updateData = [
            'status' => 'returned',
            'returned_date' => Carbon::now()->toDateString(),
            'returned_by' => $currentUser->id
        ];

        // Add return reason if provided
        if ($request->filled('return_reason')) {
            $updateData['return_reason'] = $request->return_reason;
            $updateData['return_notes'] = $request->return_notes;
        }

        $borrowing->update($updateData);

        // Increase available copies (except if book is lost)
        if ($request->return_reason !== 'book_lost') {
            $borrowing->book->increment('available_copies');
        }

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    public function returnPage()
    {
        $user = Auth::user();
        
        // Get active borrowings for the user
        $activeBorrowings = Borrowing::with(['book'])
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
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
}