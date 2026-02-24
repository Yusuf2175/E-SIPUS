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

        // Check if user has unpaid penalties
        $unpaidPenalties = Borrowing::where('user_id', Auth::id())
            ->where('penalty_amount', '>', 0)
            ->where('penalty_paid', false)
            ->count();

        if ($unpaidPenalties > 0) {
            return redirect()->back()->with('error', 'You have unpaid penalties! Please pay your penalties before borrowing new books. Contact admin or staff for payment.');
        }

        $book = Book::findOrFail($request->book_id);

        // Calculate actual available copies
        $activeBorrowingsCount = $book->activeBorrowings()->count();
        $actualAvailableCopies = $book->total_copies - $activeBorrowingsCount;

        // Check if book is still available
        if ($actualAvailableCopies <= 0) {
            return redirect()->back()->with('error', 'Book is not available for borrowing! All copies are currently borrowed.');
        }

        // Check if user already borrowed this book and hasn't returned it
        $existingBorrowing = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->first();

        if ($existingBorrowing) {
            return redirect()->back()->with('error', 'You have already borrowed this book!');
        }

        // Check borrowing limit (max 3 books per user)
        $activeBorrowings = Borrowing::where('user_id', Auth::id())
            ->where('status', 'borrowed')
            ->count();

        if ($activeBorrowings >= 3) {
            return redirect()->back()->with('error', 'You have reached the maximum borrowing limit (3 books)!');
        }

        // Create borrowing record
        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrowed_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addDays(14)->toDateString(), // 2 weeks borrowing period
            'status' => 'borrowed',
            'notes' => $request->notes,
            'approved_by' => Auth::id() 
        ]);

        // Decrease available copies
        $book->decrement('available_copies');

        return redirect()->back()->with('success', 'Book successfully borrowed! Please return before ' . $borrowing->due_date->format('d/m/Y'));
    }

    public function return(Request $request, Borrowing $borrowing)
    {
        $currentUser = Auth::user();
        $borrower = $borrowing->user;
        
        // Check if book is already returned
        if ($borrowing->status !== 'borrowed') {
            return redirect()->back()->with('error', 'Book has already been returned!');
        }
        
        // Authorization logic based on borrower's role
        if ($borrower->isAdmin()) {
            // If admin borrowed the book, only admin can return it
            if (!$currentUser->isAdmin()) {
                return redirect()->back()->with('error', 'Only Admin can return books borrowed by Admin!');
            }
        } elseif ($borrower->isPetugas()) {
            // If petugas borrowed the book, only petugas can return it
            if (!$currentUser->isPetugas()) {
                return redirect()->back()->with('error', 'Only Staff can return books borrowed by Staff!');
            }
        } else {
            // Regular user - the user themselves, admin, or petugas can return it
            if ($borrowing->user_id !== $currentUser->id && !$currentUser->isAdmin() && !$currentUser->isPetugas()) {
                return redirect()->back()->with('error', 'You do not have access to return this book!');
            }
        }

        // Validate return reason if returned by admin/petugas for user's book
        if (($currentUser->isAdmin() || $currentUser->isPetugas()) && $borrower->isUser()) {
            $request->validate([
                'return_reason' => 'required|in:normal,user_missing,late_return,book_damaged,book_lost',
                'return_notes' => 'nullable|string|max:500',
                'penalty_amount' => 'nullable|numeric|min:0',
                'penalty_notes' => 'nullable|string|max:500'
            ], [
                'return_reason.required' => 'Return reason must be selected',
                'return_reason.in' => 'Invalid return reason',
                'penalty_amount.numeric' => 'Penalty amount must be a number',
                'penalty_amount.min' => 'Penalty amount cannot be negative'
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

        // Calculate and apply penalty
        $penaltyAmount = 0;
        $penaltyType = 'none';

        // Check if manual penalty is provided
        if ($request->filled('penalty_amount') && $request->penalty_amount > 0) {
            $penaltyAmount = $request->penalty_amount;
            
            // Determine penalty type based on return reason
            if ($request->return_reason === 'late_return') {
                $penaltyType = 'late';
            } elseif ($request->return_reason === 'book_damaged') {
                $penaltyType = 'damaged';
            } elseif ($request->return_reason === 'book_lost') {
                $penaltyType = 'lost';
            } else {
                // Check if late
                if (Carbon::now()->gt($borrowing->due_date)) {
                    $penaltyType = 'late';
                }
            }
        } else {
            // Auto-calculate penalty if not manually provided
            if ($request->return_reason === 'late_return') {
                // Calculate late penalty
                if (Carbon::now()->gt($borrowing->due_date)) {
                    $daysLate = Carbon::now()->diffInDays($borrowing->due_date);
                    $penaltyAmount += $daysLate * 1000; // Rp 1,000 per day
                    $penaltyType = 'late';
                }
            } elseif ($request->return_reason === 'book_damaged') {
                $penaltyAmount += 50000; // Rp 50,000 for damaged book
                $penaltyType = 'damaged';
            } elseif ($request->return_reason === 'book_lost') {
                $penaltyAmount += 100000; // Rp 100,000 for lost book
                $penaltyType = 'lost';
            }
            
            // Also check for late return even if not explicitly selected
            if ($request->return_reason !== 'late_return' && Carbon::now()->gt($borrowing->due_date)) {
                $daysLate = Carbon::now()->diffInDays($borrowing->due_date);
                $penaltyAmount += $daysLate * 1000; // Rp 1,000 per day
                
                // If no other penalty type, set to late
                if ($penaltyType === 'none') {
                    $penaltyType = 'late';
                }
            }
        }

        // Add penalty data if there's a penalty
        if ($penaltyAmount > 0) {
            $updateData['penalty_amount'] = $penaltyAmount;
            $updateData['penalty_type'] = $penaltyType;
            $updateData['penalty_paid'] = false;
            $updateData['penalty_notes'] = $request->penalty_notes;
        }

        $borrowing->update($updateData);

        // Increase available copies (except if book is lost)
        if ($request->return_reason !== 'book_lost') {
            $borrowing->book->increment('available_copies');
        }

        $message = 'Book successfully returned!';
        if ($penaltyAmount > 0) {
            $message .= ' Penalty: Rp ' . number_format($penaltyAmount, 0, ',', '.');
        }

        return redirect()->back()->with('success', $message);
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

    public function destroy(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only allow deletion of returned borrowings
        if ($borrowing->status !== 'returned') {
            return redirect()->back()->with('error', 'Only returned borrowing records can be deleted!');
        }
        
        // Authorization: Admin/Petugas can delete any returned borrowing, users can only delete their own
        if (!$user->isAdmin() && !$user->isPetugas() && $borrowing->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have access to delete this record!');
        }
        
        // Check if user has unpaid penalty - users cannot delete records with unpaid penalties
        if ($user->isUser() && $borrowing->penalty_amount > 0 && !$borrowing->penalty_paid) {
            return redirect()->back()->with('error', 'Cannot delete borrowing history with unpaid penalty! Please contact admin/staff to pay the penalty first.');
        }
        
        $borrowing->delete();
        
        return redirect()->back()->with('success', 'Borrowing history successfully deleted!');
    }

    public function markPenaltyPaid(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can mark penalty as paid
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have access to mark penalty as paid!');
        }
        
        // Check if borrowing has penalty
        if ($borrowing->penalty_amount <= 0) {
            return redirect()->back()->with('error', 'This borrowing has no penalty!');
        }
        
        // Check if already paid
        if ($borrowing->penalty_paid) {
            return redirect()->back()->with('error', 'Penalty has already been paid!');
        }
        
        $borrowing->update([
            'penalty_paid' => true,
            'penalty_notes' => $request->payment_notes ?? 'Penalty paid'
        ]);
        
        return redirect()->back()->with('success', 'Penalty marked as paid successfully!');
    }

    public function cancelPenalty(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can cancel penalty
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have access to cancel penalty!');
        }
        
        // Check if borrowing is returned
        if ($borrowing->status !== 'returned') {
            return redirect()->back()->with('error', 'Can only cancel penalty for returned books!');
        }
        
        // Check if penalty is already paid
        if ($borrowing->penalty_paid) {
            return redirect()->back()->with('error', 'Cannot cancel penalty that has been paid!');
        }
        
        // Restore to borrowed status to allow re-processing
        $borrowing->update([
            'status' => 'borrowed',
            'returned_date' => null,
            'returned_by' => null,
            'return_reason' => null,
            'return_notes' => null,
            'penalty_amount' => 0,
            'penalty_type' => 'none',
            'penalty_paid' => false,
            'penalty_notes' => null
        ]);
        
        // Decrease available copies (restore the borrowed state)
        if ($borrowing->book->available_copies > 0) {
            $borrowing->book->decrement('available_copies');
        }
        
        return redirect()->back()->with('success', 'Penalty cancelled! Book status restored to borrowed. Please re-process the return.');
    }

    public function cancelReturn(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only admin and petugas can cancel return
        if (!$user->isAdmin() && !$user->isPetugas()) {
            return redirect()->back()->with('error', 'You do not have access to cancel return!');
        }
        
        // Check if borrowing is returned
        if ($borrowing->status !== 'returned') {
            return redirect()->back()->with('error', 'This book has not been returned yet!');
        }
        
        // Check if penalty is unpaid
        if ($borrowing->penalty_amount > 0 && !$borrowing->penalty_paid) {
            return redirect()->back()->with('error', 'Cannot cancel return with unpaid penalty! Please cancel penalty first or mark as paid.');
        }
        
        // Restore to borrowed status
        $borrowing->update([
            'status' => 'borrowed',
            'returned_date' => null,
            'returned_by' => null,
            'return_reason' => null,
            'return_notes' => null,
            'penalty_amount' => 0,
            'penalty_type' => 'none',
            'penalty_paid' => false,
            'penalty_notes' => null
        ]);
        
        // Decrease available copies (restore the borrowed state)
        if ($borrowing->return_reason !== 'book_lost') {
            $borrowing->book->decrement('available_copies');
        }
        
        return redirect()->back()->with('success', 'Return cancelled successfully! Book status restored to borrowed.');
    }
}
