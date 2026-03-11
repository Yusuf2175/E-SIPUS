<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected $reviewService;

    /**
     * Create a new controller instance.
     */
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $filters = $request->only(['rating', 'book_id', 'search']);
        $reviews = $this->reviewService->getFilteredReviews($user, $filters, 15);
        
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $stats = $this->reviewService->getReviewStats();
        }
        
        $books = $this->reviewService->getBooksForFilter();
        
        return view('reviews.index', compact('reviews', 'stats', 'books'));
    }

    /**
     * Store a newly created review.
     */
    public function store(ReviewStoreRequest $request)
    {
        try {
            $this->reviewService->createReview(Auth::id(), $request->validated());
            
            return back()->with('success', 'Ulasan berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified review.
     */
    public function update(ReviewUpdateRequest $request, Review $review)
    {
        try {
            $this->reviewService->updateReview($review, Auth::id(), $request->validated());
            
            return back()->with('success', 'Ulasan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        try {
            $this->reviewService->deleteReview($review, Auth::id());
            
            return back()->with('success', 'Ulasan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified review by admin/petugas.
     */
    public function adminDestroy(Review $review)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isPetugas()) {
            abort(403);
        }
        
        $this->reviewService->adminDeleteReview($review);
        
        return back()->with('success', 'Ulasan berhasil dihapus');
    }
}
