<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\PetugasStoreRequest;
use App\Http\Requests\PetugasUpdateRequest;
use App\Services\PetugasManagementService;

class PetugasController extends Controller
{
    protected $petugasService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PetugasManagementService $petugasService)
    {
        $this->petugasService = $petugasService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of petugas.
     */
    public function index()
    {
        $petugas = $this->petugasService->getAllPetugas();
        
        return view('admin.petugas', compact('petugas'));
    }

    /**
     * Store a newly created petugas.
     */
    public function store(PetugasStoreRequest $request)
    {
        try {
            $this->petugasService->createPetugas($request->validated());
            
            return redirect()->route('admin.petugas.index')
                ->with('success', 'Staff member added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified petugas.
     */
    public function update(PetugasUpdateRequest $request, User $petugas)
    {
        try {
            $this->petugasService->updatePetugas($petugas, $request->validated());
            
            return redirect()->route('admin.petugas.index')
                ->with('success', 'Staff member updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified petugas.
     */
    public function destroy(User $petugas)
    {
        try {
            $this->petugasService->deletePetugas($petugas);
            
            return redirect()->route('admin.petugas.index')
                ->with('success', 'Staff member deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.petugas.index')
                ->with('error', $e->getMessage());
        }
    }
}

