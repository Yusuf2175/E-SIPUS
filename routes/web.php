<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landingPage');  

// Redirect dashboard lama ke role-based dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'petugas':
            return redirect()->route('petugas.dashboard');
        default:
            return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// User Dashboard Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/request-role', [UserDashboardController::class, 'requestRole'])->name('request.role');
});

// Books Routes (accessible by all authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show');
    
    // Borrowing routes
    Route::get('/borrowings', [App\Http\Controllers\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [App\Http\Controllers\BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/borrowings/{borrowing}/return', [App\Http\Controllers\BorrowingController::class, 'return'])->name('borrowings.return');
    Route::get('/borrowings/{borrowing}', [App\Http\Controllers\BorrowingController::class, 'show'])->name('borrowings.show');
    
    // Collection routes
    Route::get('/collections', [App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [App\Http\Controllers\CollectionController::class, 'store'])->name('collections.store');
    Route::delete('/collections/{collection}', [App\Http\Controllers\CollectionController::class, 'destroy'])->name('collections.destroy');
    
    // Review routes
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Book Management Routes (Admin and Petugas only)
Route::middleware(['auth', 'verified', 'role:admin,petugas'])->group(function () {
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::patch('/books/{book}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
});

// Petugas Dashboard Routes
Route::middleware(['auth', 'verified', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'manageUsers'])->name('users');
    Route::get('/role-requests', [AdminDashboardController::class, 'manageRoleRequests'])->name('role.requests');
    Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.update.role');
    Route::patch('/role-requests/{roleRequest}', [AdminDashboardController::class, 'approveRoleRequest'])->name('role.requests.approve');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
