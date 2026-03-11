<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Http\Requests\BorrowingStoreRequest;
use App\Http\Requests\BorrowingRejectRequest;
use App\Http\Requests\BorrowingReturnRequest;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BorrowingController extends Controller
{
    protected $borrowingService;

    /**
     * Create a new controller instance.
     */
    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display a listing of borrowings.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $borrowings = $this->borrowingService->getFilteredBorrowings(
            $user, 
            $request->input('status'), 
            6
        );
        
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $stats = $this->borrowingService->getBorrowingStats($user);
        }
        
        return view('borrowings.index', compact('borrowings', 'stats'));
    }

    /**
     * Store a new borrowing request.
     */
    public function store(BorrowingStoreRequest $request)
    {
        try {
            $userId = $request->filled('user_id') && (Auth::user()->isAdmin() || Auth::user()->isPetugas()) 
                ? $request->user_id 
                : Auth::id();

            $borrowing = $this->borrowingService->createBorrowingRequest(
                $request->book_id,
                $userId,
                $request->notes
            );

            return redirect()->route('books.show', $borrowing->book)
                ->with('success', 'Borrowing request submitted successfully! Please wait for admin/staff approval.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a borrowing request.
     */
    public function approve(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        if (!$this->borrowingService->canManageBorrowing($user, $borrowing)) {
            return redirect()->back()->with('error', 'You do not have permission to approve this borrowing request!');
        }

        try {
            $this->borrowingService->approveBorrowing($borrowing, $user->id);
            
            return redirect()->back()->with('success', 'Borrowing request approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a borrowing request.
     */
    public function reject(BorrowingRejectRequest $request, Borrowing $borrowing)
    {
        $user = Auth::user();
        
        if (!$this->borrowingService->canManageBorrowing($user, $borrowing)) {
            return redirect()->back()->with('error', 'You do not have permission to reject this borrowing request!');
        }

        try {
            $this->borrowingService->rejectBorrowing(
                $borrowing, 
                $user->id, 
                $request->reject_reason
            );
            
            return redirect()->back()->with('success', 'Borrowing request rejected successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Request return of a borrowed book.
     */
    public function return(BorrowingReturnRequest $request, Borrowing $borrowing)
    {
        try {
            $this->borrowingService->requestReturn(
                $borrowing,
                $request->return_reason,
                $request->return_notes
            );
            
            return redirect()->back()->with('success', 'Return request submitted successfully! Please wait for approval.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a return request.
     */
    public function approveReturn(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        if (!$this->borrowingService->canManageBorrowing($user, $borrowing)) {
            return redirect()->back()->with('error', 'You do not have permission to approve this return request!');
        }

        try {
            $this->borrowingService->approveReturn($borrowing, $user->id);
            
            return redirect()->back()->with('success', 'Return request approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display return page with active borrowings.
     */
    public function returnPage()
    {
        $activeBorrowings = $this->borrowingService->getActiveBorrowings(Auth::id());
        
        return view('borrowings.return', compact('activeBorrowings'));
    }

    /**
     * Display the specified borrowing.
     */
    public function show(Borrowing $borrowing)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isPetugas() && $borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['book', 'user', 'approvedBy']);
        
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Hide borrowing from user's history.
     */
    public function hideHistory(Borrowing $borrowing)
    {
        try {
            $this->borrowingService->hideBorrowing($borrowing, Auth::id());
            
            return redirect()->back()->with('success', 'History deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Unhide borrowing from user's history.
     */
    public function unhideHistory(Borrowing $borrowing)
    {
        try {
            $this->borrowingService->unhideBorrowing($borrowing, Auth::id());
            
            return redirect()->back()->with('success', 'History restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Permanently delete a borrowing record (Admin only).
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admin can permanently delete borrowing records!');
        }

        $bookTitle = $borrowing->book->title;
        $borrowing->delete();

        return redirect()->back()->with('success', "Borrowing record for '{$bookTitle}' has been permanently deleted!");
    }

    /**
     * Print borrowing receipt as PDF.
     */
    public function printReceipt(Borrowing $borrowing)
    {
        $user = Auth::user();
        
        if ($user->role === 'user' && $borrowing->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $borrowing->load(['user', 'book', 'approvedBy']);

        $pdf = PDF::loadView('borrowings.pdf.receipt', compact('borrowing'));
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'bukti-peminjaman-' . str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        
        return $pdf->download($filename);
    }
}
