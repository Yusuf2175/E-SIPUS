<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $exists = Review::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk buku ini');
        }

        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $review->update([
            'review' => $request->review,
            'rating' => $request->rating
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build query based on user role
        $query = Review::with(['book', 'user']);
        
        // User can only see their own reviews
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }
        // Admin and Petugas can see all reviews
        
        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        // Filter by book
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }
        
        // Search by review content or user name
        if ($request->filled('search')) {
            $search = $request->search;
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
        
        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics for Admin/Petugas
        $stats = null;
        if ($user->isAdmin() || $user->isPetugas()) {
            $stats = [
                'total_reviews' => Review::count(),
                'average_rating' => round(Review::avg('rating'), 1),
                'five_star' => Review::where('rating', 5)->count(),
                'four_star' => Review::where('rating', 4)->count(),
                'three_star' => Review::where('rating', 3)->count(),
                'two_star' => Review::where('rating', 2)->count(),
                'one_star' => Review::where('rating', 1)->count(),
            ];
        }
        
        // Get books for filter dropdown
        $books = Book::orderBy('title')->get();
        
        return view('reviews.index', compact('reviews', 'stats', 'books'));
    }

    public function adminDestroy(Review $review)
    {
        $user = Auth::user();
        
        // Only admin and petugas can delete any review
        if (!$user->isAdmin() && !$user->isPetugas()) {
            abort(403);
        }
        
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus');
    }
}
