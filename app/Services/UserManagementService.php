<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserManagementService
{
    /**
     * Get regular users with pagination.
     */
    public function getRegularUsers(int $perPage = 10): LengthAwarePaginator
    {
        return User::where('role', 'user')
            ->oldest()
            ->paginate($perPage);
    }

    /**
     * Update user role.
     */
    public function updateUserRole(User $user, string $role): User
    {
        $user->update(['role' => $role]);
        
        return $user->fresh();
    }

    /**
     * Delete user with validation.
     */
    public function deleteUser(User $user, int $currentUserId): string
    {
        // Prevent deleting self
        if ($user->id === $currentUserId) {
            throw new \Exception('You cannot delete your own account!');
        }

        // Check if user has active borrowings
        $activeBorrowings = $user->activeBorrowings()->count();
        if ($activeBorrowings > 0) {
            throw new \Exception('Cannot delete user with active borrowings! User has ' . $activeBorrowings . ' book(s) currently borrowed.');
        }

        $userName = $user->name;
        $user->delete();

        return $userName;
    }

    /**
     * Check if user can be deleted.
     */
    public function canDeleteUser(User $user, int $currentUserId): bool
    {
        if ($user->id === $currentUserId) {
            return false;
        }

        return $user->activeBorrowings()->count() === 0;
    }

    /**
     * Get user statistics.
     */
    public function getUserStats(User $user): array
    {
        return [
            'total_borrowings' => $user->borrowings()->count(),
            'active_borrowings' => $user->activeBorrowings()->count(),
            'completed_borrowings' => $user->borrowings()->where('status', 'returned')->count(),
            'overdue_borrowings' => $user->borrowings()
                ->where('status', 'borrowed')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
        ];
    }
}
