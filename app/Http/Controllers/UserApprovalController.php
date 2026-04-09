<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = User::where('role', 'user')
            ->orderByRaw("CASE WHEN account_status = 'pending' THEN 0 WHEN account_status = 'rejected' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('account_status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'  => User::where('role', 'user')->where('account_status', 'pending')->count(),
            'approved' => User::where('role', 'user')->where('account_status', 'approved')->count(),
            'rejected' => User::where('role', 'user')->where('account_status', 'rejected')->count(),
        ];

        return view('user-approval.index', compact('users', 'status', 'stats'));
    }

    public function approve(User $user)
    {
        if ($user->account_status !== 'pending') {
            return redirect()->back()->with('error', 'This account is not pending approval.');
        }

        $user->update([
            'account_status'   => 'approved',
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', $user->name . '\'s account has been approved.');
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $user->update([
            'account_status'   => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', $user->name . '\'s account has been rejected.');
    }
}
