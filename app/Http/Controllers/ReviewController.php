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
}
