<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReviewService
{
    /**
     * Get filtered reviews with pagination.
     */
    public function getFilteredReviews(User $user, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Review::with(['book', 'user']);
        
        // Apply role-based filtering
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }
        
        // Apply filters
        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }
        
        if (!empty($filters['book_id'])) {
            $query->where('book_id', $filters['book_id']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('review', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get review statistics.
     */
    public function getReviewStats(): array
    {
        return [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating'), 1),
            'five_star' => Review::where('rating', 5)->count(),
            'four_star' => Review::where('rating', 4)->count(),
            'three_star' => Review::where('rating', 3)->count(),
            'two_star' => Review::where('rating', 2)->count(),
            'one_star' => Review::where('rating', 1)->count(),
        ];
    }

    /**
     * Create a new review.
     */
    public function createReview(int $userId, array $data): Review
    {
        // Check if user already has a review for this book
        $this->validateNoExistingReview($userId, $data['book_id']);
        
        // Check if user has approved borrowing
        $this->validateHasApprovedBorrowing($userId, $data['book_id']);

        return Review::create([
            'user_id' => $userId,
            'book_id' => $data['book_id'],
            'review' => $data['review'],
            'rating' => $data['rating']
        ]);
    }

    /**
     * Validate no existing review.
     */
    protected function validateNoExistingReview(int $userId, int $bookId): void
    {
        $exists = Review::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();

        if ($exists) {
            throw new \Exception('Anda sudah memberikan ulasan untuk buku ini');
        }
    }

    /**
     * Validate user has approved borrowing.
     */
    protected function validateHasApprovedBorrowing(int $userId, int $bookId): void
    {
        $hasApprovedBorrowing = Borrowing::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereIn('status', ['approved', 'borrowed', 'returned'])
            ->exists();

        if (!$hasApprovedBorrowing) {
            throw new \Exception('Anda harus meminjam buku ini terlebih dahulu sebelum dapat memberikan review');
        }
    }

    /**
     * Update an existing review.
     */
    public function updateReview(Review $review, int $userId, array $data): Review
    {
        if ($review->user_id !== $userId) {
            throw new \Exception('You are not authorized to update this review!');
        }

        $review->update([
            'review' => $data['review'],
            'rating' => $data['rating']
        ]);

        return $review->fresh();
    }

    /**
     * Delete a review.
     */
    public function deleteReview(Review $review, int $userId): void
    {
        if ($review->user_id !== $userId) {
            throw new \Exception('You are not authorized to delete this review!');
        }

        $review->delete();
    }

    /**
     * Delete review by admin/petugas.
     */
    public function adminDeleteReview(Review $review): void
    {
        $review->delete();
    }

    /**
     * Check if user can review a book.
     */
    public function canReviewBook(int $userId, int $bookId): bool
    {
        // Check if already reviewed
        $hasReview = Review::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();

        if ($hasReview) {
            return false;
        }

        // Check if has approved borrowing
        return Borrowing::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereIn('status', ['approved', 'borrowed', 'returned'])
            ->exists();
    }

    /**
     * Get reviews for a specific book.
     */
    public function getBookReviews(int $bookId, int $perPage = 10): LengthAwarePaginator
    {
        return Review::with('user')
            ->where('book_id', $bookId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get user's reviews.
     */
    public function getUserReviews(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Review::with('book')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all books for filter dropdown.
     */
    public function getBooksForFilter(): Collection
    {
        return Book::orderBy('title')->get();
    }
}
