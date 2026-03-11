<?php

namespace App\Http\Controllers;

use App\Services\PetugasDashboardService;
use Illuminate\Support\Facades\Auth;

class PetugasDashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PetugasDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display petugas dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        $stats = $this->dashboardService->getDashboardStats($user->id);
        $recentBorrowings = $this->dashboardService->getRecentBorrowings(6);
        $overdueBorrowings = $this->dashboardService->getOverdueBorrowingsList(5);
        $lowStockBooks = $this->dashboardService->getLowStockBooks(2, 5);
        $myActiveBorrowings = $this->dashboardService->getUserActiveBorrowingsCount($user->id);
        
        return view('dashboards.petugas', compact(
            'user', 
            'stats', 
            'recentBorrowings', 
            'overdueBorrowings', 
            'lowStockBooks', 
            'myActiveBorrowings'
        ));
    }

    /**
     * Display users with borrowing records.
     */
    public function manageUsers()
    {
        $users = $this->dashboardService->getUsersWithBorrowings(10);
        
        return view('petugas.users', compact('users'));
    }

    /**
     * Display all users (read-only view).
     */
    public function viewUsersList()
    {
        $users = $this->dashboardService->getAllUsers(10);
        
        return view('petugas.users-list', compact('users'));
    }
}
