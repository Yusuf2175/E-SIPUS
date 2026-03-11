<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Services\AdminDashboardService;
use App\Services\UserManagementService;

class AdminDashboardController extends Controller
{
    protected $dashboardService;
    protected $userManagementService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        AdminDashboardService $dashboardService,
        UserManagementService $userManagementService
    ) {
        $this->dashboardService = $dashboardService;
        $this->userManagementService = $userManagementService;
    }

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        $stats = $this->dashboardService->getDashboardStats($user->id);
        $recentUsers = $this->dashboardService->getRecentUsers(6);
        $recentBorrowings = $this->dashboardService->getRecentBorrowings(6);
        $myActiveBorrowings = $this->dashboardService->getUserActiveBorrowingsCount($user->id);
        
        return view('dashboards.admin', compact(
            'user', 
            'stats',
            'recentUsers',
            'recentBorrowings',
            'myActiveBorrowings',
        ));
    }

    /**
     * Display user management page.
     */
    public function manageUsers()
    {
        $users = $this->userManagementService->getRegularUsers(10);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(UpdateUserRoleRequest $request, User $user)
    {
        try {
            $this->userManagementService->updateUserRole($user, $request->validated()['role']);
            
            return back()->with('success', 'Role user berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user role: ' . $e->getMessage());
        }
    }

    /**
     * Delete user.
     */
    public function destroyUser(User $user)
    {
        try {
            $userName = $this->userManagementService->deleteUser($user, auth()->id());
            
            return back()->with('success', 'User "' . $userName . '" has been deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
