<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Get borrowing report data.
     */
    public function getBorrowingReportData(Carbon $startDate, Carbon $endDate, string $status = 'all'): array
    {
        $query = Borrowing::with(['book', 'user', 'approvedBy'])
            ->whereBetween('borrowed_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now()->toDateString());
            } else {
                $query->where('status', $status);
            }
        }

        $borrowings = $query->orderBy('borrowed_date', 'desc')->get();

        $stats = [
            'total' => $borrowings->count(),
            'borrowed' => $borrowings->where('status', 'borrowed')->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
            'overdue' => $borrowings->where('status', 'borrowed')
                ->filter(function ($b) {
                    return Carbon::parse($b->due_date)->isPast();
                })->count(),
        ];

        return compact('borrowings', 'stats');
    }

    /**
     * Get book report data.
     */
    public function getBookReportData(?string $category = null, ?string $availability = null): array
    {
        $query = Book::with(['addedBy']);

        if ($category) {
            $query->where('category', $category);
        }

        if ($availability === 'available') {
            $query->where('is_available', true);
        } elseif ($availability === 'borrowed') {
            $query->where('is_available', false);
        }

        $books = $query->orderBy('title')->get();

        $stats = [
            'total' => $books->count(),
            'available' => $books->filter(function($book) { 
                return $book->getActualAvailableCopies() > 0; 
            })->count(),
            'borrowed' => $books->filter(function($book) { 
                return $book->getActualAvailableCopies() == 0; 
            })->count(),
            'categories' => $books->pluck('category')->unique()->count(),
        ];

        return compact('books', 'stats');
    }

    /**
     * Get user report data.
     */
    public function getUserReportData(?string $role = null, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = User::query();

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ]);
        }

        $users = $query->withCount([
            'borrowings',
            'activeBorrowings',
            'reviews'
        ])->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $users->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'petugas' => $users->where('role', 'petugas')->count(),
            'users' => $users->where('role', 'user')->count(),
            'total_borrowings' => $users->sum('borrowings_count'),
            'active_borrowings' => $users->sum('active_borrowings_count'),
        ];

        return compact('users', 'stats');
    }

    /**
     * Get return report data.
     */
    public function getReturnReportData(Carbon $startDate, Carbon $endDate, string $returnStatus = 'all'): array
    {
        $query = Borrowing::with(['book', 'user', 'returnedBy'])
            ->where('status', 'returned')
            ->whereBetween('returned_date', [$startDate, $endDate]);

        $returns = $query->orderBy('returned_date', 'desc')->get();

        if ($returnStatus === 'on_time') {
            $returns = $returns->filter(function ($return) {
                $dueDate = Carbon::parse($return->due_date);
                $returnDate = Carbon::parse($return->returned_date);
                return $returnDate->lte($dueDate);
            });
        } elseif ($returnStatus === 'late') {
            $returns = $returns->filter(function ($return) {
                $dueDate = Carbon::parse($return->due_date);
                $returnDate = Carbon::parse($return->returned_date);
                return $returnDate->gt($dueDate);
            });
        }

        $onTimeCount = 0;
        $lateCount = 0;
        $totalLateDays = 0;

        foreach ($returns as $return) {
            $dueDate = Carbon::parse($return->due_date);
            $returnDate = Carbon::parse($return->returned_date);
            
            if ($returnDate->gt($dueDate)) {
                $lateCount++;
                $totalLateDays += $returnDate->diffInDays($dueDate);
            } else {
                $onTimeCount++;
            }
        }

        $stats = [
            'total' => $returns->count(),
            'on_time' => $onTimeCount,
            'late' => $lateCount,
            'avg_late_days' => $lateCount > 0 ? round($totalLateDays / $lateCount, 1) : 0,
        ];

        return compact('returns', 'stats');
    }
}
