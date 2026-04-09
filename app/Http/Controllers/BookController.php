<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Services\BookService;
use App\Services\LibraryLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    protected $bookService;
    protected $locationService;

    public function __construct(BookService $bookService, LibraryLocationService $locationService)
    {
        $this->bookService     = $bookService;
        $this->locationService = $locationService;
    }

    public function index(Request $request)
    {
        $filters         = $request->only(['category', 'search', 'available', 'added_by', 'region']);
        $userProvince    = null;
        $nearbyProvinces = [];

        if (Auth::check()) {
            $userProvince    = Auth::user()->province;
            $nearbyProvinces = $userProvince
                ? $this->locationService->getNeighborProvinces($userProvince)
                : [];
        }

        $books        = $this->bookService->getFilteredBooks($filters, 12, $userProvince, $nearbyProvinces);
        $categories   = $this->bookService->getCategoryNames();
        $allProvinces = $this->locationService->getProvinces();

        return view('books.index', compact('books', 'categories', 'userProvince', 'nearbyProvinces', 'allProvinces'));
    }

    public function create()
    {
        $categories = $this->bookService->getCategories();
        return view('books.create', compact('categories'));
    }

    public function show(Book $book)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $data   = $this->bookService->getBookDetails($book, $userId);

        return view('books.show', [
            'book'                 => $data['book'],
            'userReview'           => $data['user_review'],
            'inCollection'         => $data['in_collection'],
            'averageRating'        => $data['average_rating'],
            'totalReviews'         => $data['total_reviews'],
            'hasApprovedBorrowing' => $data['has_approved_borrowing'],
        ]);
    }

    public function store(BookStoreRequest $request)
    {
        try {
            $this->bookService->createBook($request->validated(), Auth::id());
            return redirect()->route('books.index')->with('success', 'Book added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add book: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Book $book)
    {
        $categories = $this->bookService->getCategories();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        try {
            $this->bookService->updateBook($book, $request->validated());
            return redirect()->route('books.index')->with('success', 'Book updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update book: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Book $book)
    {
        try {
            $this->bookService->deleteBook($book);
            return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());
        }
    }
}
