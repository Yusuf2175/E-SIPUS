<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BorrowingService
{
    /**
     * Get filtered borrowings based on user role.
     */
    public function getFilteredBorrowings(User $user, ?string $status = null, int $perPage = 6): LengthAwarePaginator
    {
        $query = Borrowing::with(['book', 'user', 'approvedBy']);
        
        $this->applyRoleFilter($query, $user);
        
        if ($status) {
            $this->applyStatusFilter($query, $status);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Apply role-based filter to query.
     */
    protected function applyRoleFilter($query, User $user): void
    {
        if ($user->isUser()) {
            // User only sees their own borrowings that are not hidden
            $query->where('user_id', $user->id)
                  ->where('hidden_by_user', false);
        } elseif ($user->isPetugas()) {
            // Petugas sees borrowings for books they added
            $query->where(function($q) use ($user) {
                $q->whereHas('book', function($bookQuery) use ($user) {
                    $bookQuery->where('added_by', $user->id);
                })->where(function($hideQuery) use ($user) {
                    $hideQuery->where('user_id', '!=', $user->id)
                              ->orWhere('hidden_by_user', false);
                });
            });
        }
        // Admin sees all borrowings
    }

    /**
     * Apply status filter to query.
     */
    protected function applyStatusFilter($query, string $status): void
    {
        if ($status === 'overdue') {
            $query->whereIn('status', ['approved', 'borrowed'])
                ->where('due_date', '<', Carbon::now()->toDateString());
        } elseif ($status === 'borrowed') {
            $query->whereIn('status', ['approved', 'borrowed']);
        } else {
            $query->where('status', $status);
        }
    }

    /**
     * Get borrowing statistics for admin/petugas.
     */
    public function getBorrowingStats(User $user): array
    {
        $statsQuery = Borrowing::query();
        
        // For petugas, only count borrowings for books they added
        if ($user->isPetugas()) {
            $statsQuery->whereHas('book', function($q) use ($user) {
                $q->where('added_by', $user->id);
            });
        }
        
        return [
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

    /**
     * Create a new borrowing request.
     */
    public function createBorrowingRequest(int $bookId, int $userId, ?string $notes = null): Borrowing
    {
        $book = Book::findOrFail($bookId);

        $this->validateNoDuplicateBorrowing($userId, $bookId);
        $this->validateBorrowingLimit($userId);

        return Borrowing::create([
            'user_id'       => $userId,
            'book_id'       => $book->id,
            'borrowed_date' => Carbon::now()->toDateString(),
            'due_date'      => Carbon::now()->addDays(14)->toDateString(),
            'status'        => 'pending',
            'notes'         => $notes,
        ]);
    }

    /**
     * Validate no duplicate borrowing exists.
     */
    protected function validateNoDuplicateBorrowing(int $userId, int $bookId): void
    {
        $existingBorrowing = Borrowing::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();

        if ($existingBorrowing) {
            if ($existingBorrowing->status === 'pending') {
                throw new \Exception('You already have a pending borrowing request for this book!');
            }
            throw new \Exception('You have already borrowed this book!');
        }
    }

    /**
     * Validate borrowing limit (max 3 books).
     */
    protected function validateBorrowingLimit(int $userId): void
    {
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->count();

        if ($activeBorrowings >= 3) {
            throw new \Exception('Borrowing limit reached (3 books maximum)!');
        }
    }

    /**
     * Approve a borrowing request.
     */
    public function approveBorrowing(Borrowing $borrowing, int $approverId): void
    {
        if ($borrowing->status !== 'pending') {
            throw new \Exception('This borrowing request cannot be approved!');
        }

        $book = $borrowing->book;
        $activeBorrowingsCount = $book->activeBorrowings()->count();
        $actualAvailableCopies = $book->total_copies - $activeBorrowingsCount;

        if ($actualAvailableCopies <= 0) {
            throw new \Exception('Book is not available! All copies are currently borrowed.');
        }

        $borrowing->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => Carbon::now()
        ]);

        $book->decrement('available_copies');
    }

    /**
     * Reject a borrowing request.
     */
    public function rejectBorrowing(Borrowing $borrowing, int $approverId, string $reason): void
    {
        if ($borrowing->status !== 'pending') {
            throw new \Exception('This borrowing request cannot be rejected!');
        }

        $borrowing->update([
            'status' => 'rejected',
            'reject_reason' => $reason,
            'approved_by' => $approverId,
            'approved_at' => Carbon::now()
        ]);
    }

    /**
     * Request return of a borrowed book.
     */
    public function requestReturn(Borrowing $borrowing, ?string $reason = null, ?string $notes = null): void
    {
        if (!in_array($borrowing->status, ['approved', 'borrowed'])) {
            throw new \Exception('Book has already been returned or is pending return!');
        }

        $borrowing->update([
            'status' => 'pending_return',
            'return_requested_at' => Carbon::now(),
            'return_reason' => $reason ?? 'normal',
            'return_notes' => $notes
        ]);
    }

    /**
     * Approve a return request.
     */
    public function approveReturn(Borrowing $borrowing, int $approverId): void
    {
        if ($borrowing->status !== 'pending_return') {
            throw new \Exception('This return request cannot be approved!');
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_date' => Carbon::now()->toDateString(),
            'returned_by' => $approverId
        ]);

        // Increase available copies (except if book is lost)
        if ($borrowing->return_reason !== 'book_lost') {
            $borrowing->book->increment('available_copies');
        }
    }

    /**
     * Get active borrowings for a user.
     */
    public function getActiveBorrowings(int $userId): Collection
    {
        $borrowings = Borrowing::with(['book'])
            ->where('user_id', $userId)
            ->whereIn('status', ['approved', 'borrowed', 'pending_return'])
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Calculate overdue status
        $borrowings->each(function ($borrowing) {
            $borrowing->is_overdue = Carbon::parse($borrowing->due_date)->isPast();
            $borrowing->days_remaining = (int) Carbon::now()->diffInDays(Carbon::parse($borrowing->due_date), false);
        });
        
        return $borrowings;
    }

    /**
     * Check if user can manage borrowing (approve/reject).
     */
    public function canManageBorrowing(User $user, Borrowing $borrowing): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isPetugas()) {
            return $borrowing->book->added_by === $user->id;
        }
        
        return false;
    }

    /**
     * Hide borrowing from user's history.
     */
    public function hideBorrowing(Borrowing $borrowing, int $userId): void
    {
        if ($borrowing->user_id !== $userId) {
            throw new \Exception('You are not authorized to hide this history!');
        }

        if ($borrowing->status !== 'returned') {
            throw new \Exception('Only returned borrowings can be hidden!');
        }

        if (!$borrowing->hideFromUser()) {
            throw new \Exception('Failed to hide history!');
        }
    }

    /**
     * Unhide borrowing from user's history.
     */
    public function unhideBorrowing(Borrowing $borrowing, int $userId): void
    {
        if ($borrowing->user_id !== $userId) {
            throw new \Exception('You are not authorized to unhide this history!');
        }

        if (!$borrowing->unhideFromUser()) {
            throw new \Exception('Failed to restore history!');
        }
    }
}
