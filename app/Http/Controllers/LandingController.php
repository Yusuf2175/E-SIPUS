<?php

namespace App\Http\Controllers;

use App\Services\LandingPageService;
use Illuminate\View\View;

class LandingController extends Controller
{
    protected $landingPageService;

    /**
     * Create a new controller instance.
     */
    public function __construct(LandingPageService $landingPageService)
    {
        $this->landingPageService = $landingPageService;
    }

    /**
     * Display landing page.
     */
    public function index(): View
    {
        $data = $this->landingPageService->getLandingPageData();
        
        return view('landingPage', [
            'recommendedBooks' => $data['recommended_books'],
            'categories' => $data['categories'],
            'totalUsers' => $data['stats']['total_users'],
            'totalBooks' => $data['stats']['total_books'],
            'totalBorrowings' => $data['stats']['total_borrowings'],
        ]);
    }
}

