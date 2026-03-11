<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.petugas', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Staff member added successfully!');
    }

    public function update(Request $request, User $petugas)
    {
        // Ensure we're only updating petugas
        if ($petugas->role !== 'petugas') {
            return redirect()->route('admin.petugas.index')
                ->with('error', 'Invalid staff member!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $petugas->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $petugas->name = $request->name;
        $petugas->email = $request->email;
        
        if ($request->filled('password')) {
            $petugas->password = Hash::make($request->password);
        }
        
        $petugas->save();

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Staff member updated successfully!');
    }

    public function destroy(User $petugas)
    {
        // Ensure we're only deleting petugas
        if ($petugas->role !== 'petugas') {
            return redirect()->route('admin.petugas.index')
                ->with('error', 'Invalid staff member!');
        }

        // Check if petugas has active borrowings
        if ($petugas->borrowings()->whereIn('status', ['borrowed', 'pending_return'])->exists()) {
            return redirect()->route('admin.petugas.index')
                ->with('error', 'Cannot delete staff member with active borrowings!');
        }

        $petugas->delete();

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Staff member deleted successfully!');
    }
}
