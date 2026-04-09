<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Update user profile information.
     */
    public function updateProfile(User $user, array $data): User
    {
        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            // Hapus avatar lama
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        } else {
            unset($data['avatar']); // jangan overwrite jika tidak ada upload baru
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->fresh();
    }

    /**
     * Hapus avatar user, kembali ke default.
     */
    public function removeAvatar(User $user): User
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = null;
        $user->save();

        return $user->fresh();
    }

    /**
     * Verify user password.
     */
    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    /**
     * Delete user account with validation.
     */
    public function deleteAccount(User $user): void
    {
        // Check if user has active borrowings
        $activeBorrowings = $user->activeBorrowings()->count();
        
        if ($activeBorrowings > 0) {
            throw new \Exception('Cannot delete account with active borrowings! Please return all borrowed books first.');
        }

        $user->delete();
    }

    /**
     * Check if user can delete their account.
     */
    public function canDeleteAccount(User $user): bool
    {
        return $user->activeBorrowings()->count() === 0;
    }

    /**
     * Get user profile statistics.
     */
    public function getProfileStats(User $user): array
    {
        return [
            'total_borrowings' => $user->borrowings()->count(),
            'active_borrowings' => $user->activeBorrowings()->count(),
            'completed_borrowings' => $user->borrowings()->where('status', 'returned')->count(),
            'total_reviews' => $user->reviews()->count(),
            'collection_count' => $user->collections()->count(),
            'overdue_borrowings' => $user->borrowings()
                ->where('status', 'borrowed')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
        ];
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $newPassword): User
    {
        $user->password = Hash::make($newPassword);
        $user->save();

        return $user->fresh();
    }

    /**
     * Get user's recent activity.
     */
    public function getRecentActivity(User $user, int $limit = 10): array
    {
        return [
            'recent_borrowings' => $user->borrowings()
                ->with('book')
                ->latest()
                ->take($limit)
                ->get(),
            'recent_reviews' => $user->reviews()
                ->with('book')
                ->latest()
                ->take($limit)
                ->get(),
        ];
    }
}
