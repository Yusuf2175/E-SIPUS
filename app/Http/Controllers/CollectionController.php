<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Http\Requests\CollectionStoreRequest;
use App\Services\CollectionService;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    protected $collectionService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Display user's collection.
     */
    public function index()
    {
        $collections = $this->collectionService->getUserCollections(Auth::id());
        
        return view('collections.index', compact('collections'));
    }

    /**
     * Add a book to user's collection.
     */
    public function store(CollectionStoreRequest $request)
    {
        try {
            $this->collectionService->addToCollection(Auth::id(), $request->book_id);
            
            return back()->with('success', 'Buku berhasil ditambahkan ke koleksi');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove a book from user's collection.
     */
    public function destroy(Collection $collection)
    {
        try {
            $this->collectionService->removeFromCollection($collection, Auth::id());
            
            return back()->with('success', 'Buku berhasil dihapus dari koleksi');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
