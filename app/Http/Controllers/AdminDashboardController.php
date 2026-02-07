<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleRequest;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_petugas' => User::where('role', 'petugas')->count(),
            'total_regular_users' => User::where('role', 'user')->count(),
            'pending_requests' => RoleRequest::pending()->count(),
            'total_books' => \App\Models\Book::count(),
            'available_books' => \App\Models\Book::where('available_copies', '>', 0)->count(),
            'active_borrowings' => \App\Models\Borrowing::where('status', 'borrowed')->count(),
            'overdue_borrowings' => \App\Models\Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', \Carbon\Carbon::now()->toDateString())
                ->count(),
        ];
        
        // Recent users
        $recentUsers = User::latest()->take(5)->get();
        
        // Pending role requests
        $pendingRoleRequests = RoleRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        
        // Recent borrowings
        $recentBorrowings = \App\Models\Borrowing::with(['book', 'user'])
            ->latest()
            ->take(5)
            ->get();
        
        // My active borrowings (for CTA)
        $myActiveBorrowings = \App\Models\Borrowing::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->count();
        
        return view('dashboards.admin', compact(
            'user', 
            'stats',
            'recentUsers',
            'pendingRoleRequests',
            'recentBorrowings',
            'myActiveBorrowings'
        ));
    }

    public function manageUsers()
    {
        $users = User::latest()->paginate(10);
        
        return view('admin.users', compact('users'));
    }

    public function manageRoleRequests()
    {
        $roleRequests = RoleRequest::with(['user', 'approver'])
            ->latest()
            ->paginate(10);
        
        return view('admin.role-requests', compact('roleRequests'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,petugas,admin',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diupdate.');
    }

    public function approveRoleRequest(Request $request, RoleRequest $roleRequest)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $admin = auth()->user();

        if ($request->action === 'approve') {
            $roleRequest->update([
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            // Update user role
            $roleRequest->user->update([
                'role' => $roleRequest->requested_role
            ]);

            $message = 'Permintaan role berhasil disetujui.';
        } else {
            $roleRequest->update([
                'status' => 'rejected',
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $message = 'Permintaan role berhasil ditolak.';
        }

        return back()->with('success', $message);
    }
}
