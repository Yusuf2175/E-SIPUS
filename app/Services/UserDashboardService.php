<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Collection;

class UserDashboardService
{
    /**
     * Get dashboard statistics for user.
     */
    public function getDashboardStats(User $user): array
    {
        return [
            'active_borrowings' => $this->getActiveBorrowingsCount($user),
            'total_borrowings' => $this->getTotalBorrowingsCount($user),
            'collections' => $this->getCollectionsCount($user),
            'reviews' => $this->getReviewsCount($user),
        ];
    }

    /**
     * Get active borrowings count.
     */
    protected function getActiveBorrowingsCount(User $user): int
    {
        return $user->borrowings()
            ->whereIn('status', ['approved', 'borrowed'])
            ->count();
    }

    /**
     * Get total borrowings count.
     */
    protected function getTotalBorrowingsCount(User $user): int
    {
        return $user->borrowings()->count();
    }

    /**
     * Get collections count.
     */
    protected function getCollectionsCount(User $user): int
    {
        return $user->collections()->count();
    }

    /**
     * Get reviews count.
     */
    protected function getReviewsCount(User $user): int
    {
        return $user->reviews()->count();
    }

    /**
     * Get recent borrowings for user.
     */
    public function getRecentBorrowings(User $user, int $limit = 5): Collection
    {
        return $user->borrowings()
            ->with('book')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get recommended books for user.
     */
    public function getRecommendedBooks(int $limit = 6): Collection
    {
        return Book::available()
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    /**
     * Get user's borrowing history with statistics.
     */
    public function getBorrowingHistory(User $user): array
    {
        $borrowings = $user->borrowings()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'borrowings' => $borrowings,
            'total' => $borrowings->count(),
            'active' => $borrowings->whereIn('status', ['approved', 'borrowed'])->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
            'rejected' => $borrowings->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Get user's favorite categories based on borrowing history.
     */
    public function getFavoriteCategories(User $user, int $limit = 5): Collection
    {
        return $user->borrowings()
            ->with('book')
            ->get()
            ->pluck('book.category')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take($limit);
    }
}
