<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Auth::user()->collections()->with('book')->latest()->get();
        return view('collections.index', compact('collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $exists = Collection::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Buku sudah ada di koleksi Anda');
        }

        Collection::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan ke koleksi');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }

        $collection->delete();
        return back()->with('success', 'Buku berhasil dihapus dari koleksi');
    }
}
