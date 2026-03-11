<?php

namespace App\Http\Controllers;

use App\Services\UserDashboardService;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Create a new controller instance.
     */
    public function __construct(UserDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        $stats = $this->dashboardService->getDashboardStats($user);
        $recentBorrowings = $this->dashboardService->getRecentBorrowings($user, 5);
        $recommendedBooks = $this->dashboardService->getRecommendedBooks(6);
        
        return view('dashboards.user', compact(
            'user',
            'stats', 
            'recentBorrowings', 
            'recommendedBooks'
        ));
    }
}
