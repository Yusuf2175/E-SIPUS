<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Book;
use Illuminate\Support\Collection as LaravelCollection;

class CollectionService
{
    /**
     * Get user's collections with available books only.
     */
    public function getUserCollections(int $userId): LaravelCollection
    {
        return Collection::where('user_id', $userId)
            ->with(['book' => function($query) {
                $query->where('available_copies', '>', 0);
            }])
            ->latest()
            ->get()
            ->filter(function($collection) {
                return $collection->book !== null;
            });
    }

    /**
     * Add a book to user's collection.
     */
    public function addToCollection(int $userId, int $bookId): Collection
    {
        // Check if book exists
        $book = Book::findOrFail($bookId);

        // Check if already in collection
        if ($this->isInCollection($userId, $bookId)) {
            throw new \Exception('Buku sudah ada di koleksi Anda');
        }

        return Collection::create([
            'user_id' => $userId,
            'book_id' => $bookId
        ]);
    }

    /**
     * Remove a book from user's collection.
     */
    public function removeFromCollection(Collection $collection, int $userId): void
    {
        if ($collection->user_id !== $userId) {
            throw new \Exception('You are not authorized to remove this collection!');
        }

        $collection->delete();
    }

    /**
     * Check if book is in user's collection.
     */
    public function isInCollection(int $userId, int $bookId): bool
    {
        return Collection::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();
    }

    /**
     * Get collection count for a user.
     */
    public function getCollectionCount(int $userId): int
    {
        return Collection::where('user_id', $userId)->count();
    }

    /**
     * Get available books in user's collection.
     */
    public function getAvailableBooksInCollection(int $userId): LaravelCollection
    {
        return Collection::where('user_id', $userId)
            ->whereHas('book', function($query) {
                $query->where('available_copies', '>', 0);
            })
            ->with('book')
            ->latest()
            ->get()
            ->pluck('book');
    }

    /**
     * Remove all collections for a specific book.
     */
    public function removeBookFromAllCollections(int $bookId): int
    {
        return Collection::where('book_id', $bookId)->delete();
    }

    /**
     * Get users who have a book in their collection.
     */
    public function getUsersWithBookInCollection(int $bookId): LaravelCollection
    {
        return Collection::where('book_id', $bookId)
            ->with('user')
            ->get()
            ->pluck('user');
    }
}
