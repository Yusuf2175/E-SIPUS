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
        
        $query = Borrowing::with(['book', 'user', 'approvedBy', 'shouldBeApprovedBy']);
        
        // Filter by user role
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isPetugas()) {
            // Petugas can see:
            // 1. Borrowings that need their approval (should_be_approved_by = their id)
            // 2. Borrowings they made themselves
            $query->where(function($q) use ($user) {
                $q->where('should_be_approved_by', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        }
        // Admin can see all
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('approval_status', 'approved')
                    ->where('due_date', '<', Carbon::now()->toDateString());
            } elseif ($request->status === 'pending') {
                $query->where('approval_status', 'pending');
            } elseif ($request->status === 'approved') {
                $query->where('approval_status', 'approved');
            } elseif ($request->status === 'rejected') {
                $query->where('approval_status', 'rejected');
            } elseif ($request->status === 'return_pending') {
                $query->where('return_approval_status', 'pending');
            } else {
                $query->where('status', $request->status);
            }
        }
        
        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics for Admin/Petugas
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $statsQuery = Borrowing::query();
            
            // For petugas, only count borrowings they should approve
            if ($user->isPetugas()) {
                $statsQuery->where(function($q) use ($user) {
                    $q->where('should_be_approved_by', $user->id)
                      ->orWhere('user_id', $user->id);
                });
            }
            
            $stats = [
                'active' => (clone $statsQuery)->where('status', 'borrowed')
                    ->where('approval_status', 'approved')
                    ->count(),
                'returned' => (clone $statsQuery)->where('status', 'returned')->count(),
                'overdue' => (clone $statsQuery)->where('status', 'borrowed')
                    ->where('approval_status', 'approved')
                    ->where('due_date', '<', Carbon::now()->toDateString())
                    ->count(),
                'pending' => (clone $statsQuery)->where('approval_status', 'pending')->count(),
                'return_pending' => (clone $statsQuery)->where('return_approval_status', 'pending')->count(),
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

        // Check if user has unpaid penalties
        $unpaidPenalties = Borrowing::where('user_id', $userId)
            ->where('penalty_amount', '>', 0)
            ->where('penalty_paid', false)
            ->count();

        if ($unpaidPenalties > 0) {
            return redirect()->back()->with('error', 'This user has unpaid penalties! Please pay penalties before borrowing new books.');
        }

        $book = Book::findOrFail($request->book_id);

        // Calculate actual available copies
        $activeBorrowingsCount = $book->activeBorrowings()->count();
        $actualAvailableCopies = $book->total_copies - $activeBorrowingsCount;

        // Check if book is still available
        if ($actualAvailableCopies <= 0) {
            return redirect()->back()->with('error', 'Book is not available for borrowing! All copies are currently borrowed.');
        }

        // Check if user already has pending or active borrowing for this book
        $existingBorrowing = Borrowing::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->where(function($query) {
                $query->where('status', 'borrowed')
                      ->orWhere('approval_status', 'pending');
            })
            ->first();

        if ($existingBorrowing) {
            if ($existingBorrowing->approval_status === 'pending') {
                return redirect()->back()->with('error', 'You already have a pending request for this book!');
            }
            return redirect()->back()->with('error', 'You have already borrowed this book!');
        }

        // Check borrowing limit (max 3 books per user)
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->where(function($query) {
                $query->where('status', 'borrowed')
                      ->orWhere('approval_status', 'pending');
            })
            ->count();

        if ($activeBorrowings >= 3) {
            return redirect()->back()->with('error', 'Borrowing limit reached (3 books maximum)!');
        }

        // Create borrowing record with PENDING status
        $borrowing = Borrowing::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'borrowed_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addDays(14)->toDateString(),
            'status' => 'borrowed',
            'approval_status' => 'pending',
            'notes' => $request->notes,
            'approved_by' => null,
            'should_be_approved_by' => $book->added_by // Set to the person who added the book
        ]);

        // DO NOT decrease available copies yet - only after approval

        return redirect()->route('borrowings.index')->with('success', 'Borrowing request submitted successfully! You can track your request status in "My Borrowings" page. Waiting for approval from the staff who added this book.');
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

        // Set return approval status to pending
        $updateData['return_approval_status'] = 'pending';

        $borrowing->update($updateData);

        // DO NOT increase available copies yet - only after return approval

        $message = 'Return request submitted! Waiting for admin/staff approval.';

        return redirect()->back()->with('success', $message);
    }

    public function returnPage()
    {
        $user = Auth::user();
        
        // Get active borrowings for the user (only approved borrowings can be returned)
        $activeBorrowings = Borrowing::with(['book'])
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->where('approval_status', 'approved') // Only show approved borrowings
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

    public function approveBorrowing(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only the person who added the book can approve (or admin can approve any)
        if (!$user->isAdmin() && $borrowing->should_be_approved_by !== $user->id) {
            return redirect()->back()->with('error', 'Only the staff who added this book or admin can approve this request!');
        }
        
        // Check if already approved
        if ($borrowing->approval_status === 'approved') {
            return redirect()->back()->with('error', 'This borrowing has already been approved!');
        }
        
        // Check if rejected
        if ($borrowing->approval_status === 'rejected') {
            return redirect()->back()->with('error', 'Cannot approve a rejected borrowing!');
        }
        
        // Update borrowing status
        $borrowing->update([
            'approval_status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now()
        ]);
        
        // NOW decrease available copies
        $borrowing->book->decrement('available_copies');
        
        return redirect()->back()->with('success', 'Borrowing approved successfully! The book is now borrowed.');
    }

    public function rejectBorrowing(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only the person who added the book can reject (or admin can reject any)
        if (!$user->isAdmin() && $borrowing->should_be_approved_by !== $user->id) {
            return redirect()->back()->with('error', 'Only the staff who added this book or admin can reject this request!');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        // Check if already processed
        if ($borrowing->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This borrowing has already been processed!');
        }
        
        // Update borrowing status
        $borrowing->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);
        
        return redirect()->back()->with('success', 'Borrowing request rejected.');
    }

    public function approveReturn(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only the person who added the book can approve return (or admin can approve any)
        if (!$user->isAdmin() && $borrowing->should_be_approved_by !== $user->id) {
            return redirect()->back()->with('error', 'Only the staff who added this book or admin can approve this return!');
        }
        
        // Check if return is pending
        if ($borrowing->return_approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This return is not pending approval!');
        }
        
        // Update return approval status
        $borrowing->update([
            'return_approval_status' => 'approved',
            'return_approved_at' => Carbon::now(),
            'status' => 'returned'
        ]);
        
        // NOW increase available copies (except if book is lost)
        if ($borrowing->return_reason !== 'book_lost') {
            $borrowing->book->increment('available_copies');
        }
        
        $message = 'Return approved successfully! The book has been returned.';
        if ($borrowing->penalty_amount > 0) {
            $message .= ' Penalty: Rp ' . number_format($borrowing->penalty_amount, 0, ',', '.');
        }
        
        return redirect()->back()->with('success', $message);
    }

    public function rejectReturn(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        // Only the person who added the book can reject return (or admin can reject any)
        if (!$user->isAdmin() && $borrowing->should_be_approved_by !== $user->id) {
            return redirect()->back()->with('error', 'Only the staff who added this book or admin can reject this return!');
        }
        
        $request->validate([
            'return_rejection_reason' => 'required|string|max:500'
        ]);
        
        // Check if return is pending
        if ($borrowing->return_approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This return is not pending approval!');
        }
        
        // Update return approval status
        $borrowing->update([
            'return_approval_status' => 'rejected',
            'return_rejection_reason' => $request->return_rejection_reason,
            'status' => 'borrowed',
            'returned_date' => null
        ]);
        
        return redirect()->back()->with('success', 'Return request rejected. Book remains borrowed.');
    }
}
