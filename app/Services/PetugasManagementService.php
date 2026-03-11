<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class PetugasManagementService
{
    /**
     * Get all petugas users.
     */
    public function getAllPetugas(): Collection
    {
        return User::where('role', 'petugas')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new petugas.
     */
    public function createPetugas(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'petugas',
        ]);
    }

    /**
     * Update an existing petugas.
     */
    public function updatePetugas(User $petugas, array $data): User
    {
        // Validate that user is actually a petugas
        if ($petugas->role !== 'petugas') {
            throw new \Exception('Invalid staff member!');
        }

        $petugas->name = $data['name'];
        $petugas->email = $data['email'];
        
        if (!empty($data['password'])) {
            $petugas->password = Hash::make($data['password']);
        }
        
        $petugas->save();

        return $petugas->fresh();
    }

    /**
     * Delete a petugas with validation.
     */
    public function deletePetugas(User $petugas): void
    {
        // Validate that user is actually a petugas
        if ($petugas->role !== 'petugas') {
            throw new \Exception('Invalid staff member!');
        }

        // Check if petugas has active borrowings
        if ($this->hasActiveBorrowings($petugas)) {
            throw new \Exception('Cannot delete staff member with active borrowings!');
        }

        $petugas->delete();
    }

    /**
     * Check if petugas has active borrowings.
     */
    protected function hasActiveBorrowings(User $petugas): bool
    {
        return $petugas->borrowings()
            ->whereIn('status', ['borrowed', 'pending_return'])
            ->exists();
    }

    /**
     * Check if petugas can be deleted.
     */
    public function canDeletePetugas(User $petugas): bool
    {
        if ($petugas->role !== 'petugas') {
            return false;
        }

        return !$this->hasActiveBorrowings($petugas);
    }

    /**
     * Get petugas statistics.
     */
    public function getPetugasStats(User $petugas): array
    {
        return [
            'books_added' => $petugas->addedBooks()->count(),
            'total_borrowings' => $petugas->borrowings()->count(),
            'active_borrowings' => $petugas->borrowings()
                ->whereIn('status', ['approved', 'borrowed'])
                ->count(),
            'approved_borrowings' => $petugas->approvedBorrowings()->count(),
        ];
    }

    /**
     * Get petugas with statistics.
     */
    public function getPetugasWithStats(): Collection
    {
        return User::where('role', 'petugas')
            ->withCount([
                'addedBooks',
                'borrowings',
                'approvedBorrowings'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Validate petugas role.
     */
    public function validatePetugasRole(User $user): void
    {
        if ($user->role !== 'petugas') {
            throw new \Exception('User is not a petugas!');
        }
    }
}
