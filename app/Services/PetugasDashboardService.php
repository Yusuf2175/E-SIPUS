<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PetugasDashboardService
{
    /**
     * Get dashboard statistics for petugas.
     */
    public function getDashboardStats(int $userId): array
    {
        return [
            'total_books' => $this->getTotalBooks(),
            'available_books' => $this->getAvailableBooks(),
            'active_borrowings' => $this->getActiveBorrowings(),
            'overdue_borrowings' => $this->getOverdueBorrowings(),
            'total_users' => $this->getTotalUsers(),
            'books_added_by_me' => $this->getBooksAddedByUser($userId),
            'my_active_borrowings' => $this->getUserActiveBorrowingsCount($userId),
        ];
    }

    /**
     * Get total books count.
     */
    protected function getTotalBooks(): int
    {
        return Book::count();
    }

    /**
     * Get available books count.
     */
    protected function getAvailableBooks(): int
    {
        return Book::where('available_copies', '>', 0)->count();
    }

    /**
     * Get active borrowings count.
     */
    protected function getActiveBorrowings(): int
    {
        return Borrowing::where('status', 'borrowed')->count();
    }

    /**
     * Get overdue borrowings count.
     */
    protected function getOverdueBorrowings(): int
    {
        return Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->count();
    }

    /**
     * Get total users count.
     */
    protected function getTotalUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    /**
     * Get books added by specific user.
     */
    protected function getBooksAddedByUser(int $userId): int
    {
        return Book::where('added_by', $userId)->count();
    }

    /**
     * Get user's active borrowings count.
     */
    public function getUserActiveBorrowingsCount(int $userId): int
    {
        return Borrowing::where('user_id', $userId)
            ->whereIn('status', ['approved', 'borrowed'])
            ->count();
    }

    /**
     * Get recent borrowings with pagination.
     */
    public function getRecentBorrowings(int $perPage = 6): LengthAwarePaginator
    {
        return Borrowing::with(['book', 'user'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get overdue borrowings.
     */
    public function getOverdueBorrowingsList(int $limit = 5): Collection
    {
        return Borrowing::with(['book', 'user'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->orderBy('due_date', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get books with low stock.
     */
    public function getLowStockBooks(int $threshold = 2, int $limit = 5): Collection
    {
        return Book::where('available_copies', '<=', $threshold)
            ->where('available_copies', '>', 0)
            ->orderBy('available_copies', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get users with borrowing records.
     */
    public function getUsersWithBorrowings(int $perPage = 10): LengthAwarePaginator
    {
        return User::where('role', 'user')
            ->whereHas('borrowings')
            ->withCount(['borrowings as active_borrowings_count' => function($query) {
                $query->where('status', 'borrowed');
            }])
            ->with(['activeBorrowings' => function($query) {
                $query->with('book')->latest()->take(5);
            }])
            ->orderByDesc('active_borrowings_count')
            ->paginate($perPage);
    }

    /**
     * Get all users (read-only view).
     */
    public function getAllUsers(int $perPage = 10): LengthAwarePaginator
    {
        return User::where('role', 'user')
            ->oldest()
            ->paginate($perPage);
    }

    /**
     * Get books added by petugas with statistics.
     */
    public function getBooksAddedByPetugas(int $petugasId, int $perPage = 10): LengthAwarePaginator
    {
        return Book::where('added_by', $petugasId)
            ->withCount('borrowings')
            ->withCount(['activeBorrowings' => function($query) {
                $query->whereIn('status', ['approved', 'borrowed']);
            }])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get borrowings for books added by petugas.
     */
    public function getBorrowingsForPetugasBooks(int $petugasId, int $perPage = 10): LengthAwarePaginator
    {
        return Borrowing::with(['book', 'user'])
            ->whereHas('book', function($query) use ($petugasId) {
                $query->where('added_by', $petugasId);
            })
            ->latest()
            ->paginate($perPage);
    }
}
