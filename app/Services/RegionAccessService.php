<?php

namespace App\Services;

use App\Models\RegionAccessRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class RegionAccessService
{
    public function __construct(
        private LibraryLocationService $locationService
    ) {}

    /**
     * Cek apakah user perlu request akses untuk meminjam buku dari region tertentu.
     * Return false = bebas pinjam, return true = perlu request.
     */
    public function needsAccessRequest(User $user, ?string $bookRegion): bool
    {
        // Buku tanpa region → bebas
        if (!$bookRegion) return false;

        // Admin & petugas bebas
        if ($user->isAdmin() || $user->isPetugas()) return false;

        $userProvince = $user->province;

        // User tanpa province → perlu request
        if (!$userProvince) return true;

        // Provinsi sama → bebas
        if ($userProvince === $bookRegion) return false;

        // Provinsi tetangga → bebas
        $neighbors = $this->locationService->getNeighborProvinces($userProvince);
        if (in_array($bookRegion, $neighbors)) return false;

        // Wilayah lain → perlu request
        return true;
    }

    /**
     * Cek apakah user sudah punya akses yang disetujui untuk region tertentu.
     */
    public function hasApprovedAccess(int $userId, string $region): bool
    {
        return RegionAccessRequest::where('user_id', $userId)
            ->where('requested_region', $region)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Cek apakah user sudah punya request pending untuk region tertentu.
     */
    public function hasPendingRequest(int $userId, string $region): bool
    {
        return RegionAccessRequest::where('user_id', $userId)
            ->where('requested_region', $region)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Buat request akses wilayah baru.
     */
    public function createRequest(User $user, string $region, ?string $reason): RegionAccessRequest
    {
        // Cek sudah ada pending
        if ($this->hasPendingRequest($user->id, $region)) {
            throw new \Exception('Anda sudah memiliki permintaan akses yang sedang menunggu persetujuan untuk wilayah ' . $region . '.');
        }

        // Cek sudah approved
        if ($this->hasApprovedAccess($user->id, $region)) {
            throw new \Exception('Anda sudah memiliki akses ke wilayah ' . $region . '.');
        }

        return RegionAccessRequest::create([
            'user_id'          => $user->id,
            'requested_region' => $region,
            'user_province'    => $user->province ?? '-',
            'reason'           => $reason,
            'status'           => 'pending',
        ]);
    }

    /**
     * Approve request.
     */
    public function approveRequest(RegionAccessRequest $request, User $reviewer, ?string $note): void
    {
        $request->update([
            'status'      => 'approved',
            'reviewed_by' => $reviewer->id,
            'review_note' => $note,
            'reviewed_at' => Carbon::now(),
        ]);
    }

    /**
     * Reject request.
     */
    public function rejectRequest(RegionAccessRequest $request, User $reviewer, ?string $note): void
    {
        $request->update([
            'status'      => 'rejected',
            'reviewed_by' => $reviewer->id,
            'review_note' => $note,
            'reviewed_at' => Carbon::now(),
        ]);
    }

    /**
     * Get paginated requests untuk admin/petugas.
     */
    public function getRequests(?string $status, int $perPage = 15): LengthAwarePaginator
    {
        $query = RegionAccessRequest::with(['user', 'reviewer'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get requests milik user tertentu.
     */
    public function getUserRequests(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return RegionAccessRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
