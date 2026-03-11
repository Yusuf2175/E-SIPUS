<?php

namespace App\Services;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminDashboardService
{
    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(int $userId): array
    {
        return [
            'total_users' => $this->getTotalUsers(),
            'total_admins' => $this->getTotalAdmins(),
            'total_petugas' => $this->getTotalPetugas(),
            'total_regular_users' => $this->getTotalRegularUsers(),
            'total_categories' => $this->getTotalCategories(),
            'total_books' => $this->getTotalBooks(),
            'available_books' => $this->getAvailableBooks(),
            'active_borrowings' => $this->getActiveBorrowings(),
            'my_active_borrowings' => $this->getUserActiveBorrowingsCount($userId),
            'overdue_borrowings' => $this->getOverdueBorrowings(),
        ];
    }

    /**
     * Get total users count.
     */
    public function getTotalUsers(): int
    {
        return User::count();
    }

    /**
     * Get total admins count.
     */
    public function getTotalAdmins(): int
    {
        return User::where('role', 'admin')->count();
    }

    /**
     * Get total petugas count.
     */
    public function getTotalPetugas(): int
    {
        return User::where('role', 'petugas')->count();
    }

    /**
     * Get total regular users count.
     */
    public function getTotalRegularUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    /**
     * Get total categories count.
     */
    public function getTotalCategories(): int
    {
        return Category::count();
    }

    /**
     * Get total books count.
     */
    public function getTotalBooks(): int
    {
        return Book::count();
    }

    /**
     * Get available books count.
     */
    public function getAvailableBooks(): int
    {
        return Book::where('available_copies', '>', 0)->count();
    }

    /**
     * Get active borrowings count.
     */
    public function getActiveBorrowings(): int
    {
        return Borrowing::whereIn('status', ['approved', 'borrowed'])->count();
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
     * Get overdue borrowings count.
     */
    public function getOverdueBorrowings(): int
    {
        return Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->count();
    }

    /**
     * Get recent users with pagination.
     */
    public function getRecentUsers(int $perPage = 6): LengthAwarePaginator
    {
        return User::latest()->paginate($perPage, ['*'], 'users_page');
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
}
