<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Support\Collection;

class LandingPageService
{
    /**
     * Get recommended books based on reviews and recency.
     */
    public function getRecommendedBooks(int $limit = 6): Collection
    {
        return Book::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get top categories with book count.
     */
    public function getTopCategories(int $limit = 4): Collection
    {
        return Category::withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get landing page statistics.
     */
    public function getLandingStats(): array
    {
        return [
            'total_users' => $this->getTotalActiveUsers(),
            'total_books' => $this->getTotalBooks(),
            'total_borrowings' => $this->getTotalBorrowings(),
        ];
    }

    /**
     * Get total active users (excluding admins).
     */
    protected function getTotalActiveUsers(): int
    {
        return User::whereIn('role', ['user', 'petugas'])->count();
    }

    /**
     * Get total books count.
     */
    protected function getTotalBooks(): int
    {
        return Book::count();
    }

    /**
     * Get total borrowings count.
     */
    protected function getTotalBorrowings(): int
    {
        return Borrowing::count();
    }

    /**
     * Get all landing page data at once (optimized).
     */
    public function getLandingPageData(): array
    {
        return [
            'recommended_books' => $this->getRecommendedBooks(6),
            'categories' => $this->getTopCategories(4),
            'stats' => $this->getLandingStats(),
        ];
    }
}
