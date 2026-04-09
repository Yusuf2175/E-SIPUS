<?php

namespace App\Http\Controllers;

use App\Models\RegionAccessRequest;
use App\Services\RegionAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionAccessController extends Controller
{
    public function __construct(private RegionAccessService $service) {}

    /**
     * Halaman form request akses wilayah (user).
     */
    public function create(Request $request)
    {
        $region   = $request->query('region');
        $bookSlug = $request->query('book');

        if (!$region) {
            return redirect()->route('books.index')->with('error', 'Wilayah tidak valid.');
        }

        $user = Auth::user();

        // Cek sudah approved
        if ($this->service->hasApprovedAccess($user->id, $region)) {
            return redirect()->back()->with('info', 'Anda sudah memiliki akses ke wilayah ' . $region . '.');
        }

        // Cek sudah pending
        $hasPending = $this->service->hasPendingRequest($user->id, $region);

        return view('region-access.create', compact('region', 'bookSlug', 'hasPending'));
    }

    /**
     * Simpan request akses wilayah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->service->createRequest(Auth::user(), $request->region, $request->reason);

            $redirect = $request->book_slug
                ? redirect()->route('books.show', $request->book_slug)
                : redirect()->route('books.index');

            return $redirect->with('success', 'Permintaan akses wilayah ' . $request->region . ' telah dikirim. Tunggu persetujuan admin/petugas.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman daftar request untuk admin/petugas.
     */
    public function index(Request $request)
    {
        $status   = $request->query('status', 'all');
        $requests = $this->service->getRequests($status);
        $stats    = [
            'pending'  => RegionAccessRequest::where('status', 'pending')->count(),
            'approved' => RegionAccessRequest::where('status', 'approved')->count(),
            'rejected' => RegionAccessRequest::where('status', 'rejected')->count(),
        ];

        return view('region-access.index', compact('requests', 'status', 'stats'));
    }

    /**
     * Approve request.
     */
    public function approve(Request $request, RegionAccessRequest $regionAccess)
    {
        $request->validate(['review_note' => 'nullable|string|max:500']);

        $this->service->approveRequest($regionAccess, Auth::user(), $request->review_note);

        return redirect()->back()->with('success', 'Permintaan akses wilayah disetujui.');
    }

    /**
     * Reject request.
     */
    public function reject(Request $request, RegionAccessRequest $regionAccess)
    {
        $request->validate(['review_note' => 'nullable|string|max:500']);

        $this->service->rejectRequest($regionAccess, Auth::user(), $request->review_note);

        return redirect()->back()->with('success', 'Permintaan akses wilayah ditolak.');
    }
}
