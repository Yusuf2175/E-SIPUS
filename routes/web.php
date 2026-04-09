<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Wilayah API proxy (cached, no auth needed for dropdown)
Route::prefix('api/wilayah')->name('wilayah.')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'provinces'])->name('provinces');
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'regencies'])->name('regencies');
});

Route::get('/', [LandingController::class, 'index'])->name('landingPage');

// Halaman pending setelah register
Route::get('/register/pending', [App\Http\Controllers\Auth\RegisteredUserController::class, 'pending'])
    ->name('register.pending');

// Redirect to role-based dashboard
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
});

// Book Management Routes (Admin and Petugas only) - MUST BE BEFORE /books/{book}
Route::middleware(['auth', 'verified', 'role:admin,petugas'])->group(function () {
    Route::get('/books/create', [App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [App\Http\Controllers\BookController::class, 'edit'])->name('books.edit');
    Route::patch('/books/{book}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
    
    // Category Management Routes
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Admin delete review
    Route::delete('/reviews/{review}/admin', [App\Http\Controllers\ReviewController::class, 'adminDestroy'])->name('reviews.admin.destroy');
});

// Books Routes (accessible by all authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show');

    // Region Access Request (user)
    Route::get('/region-access/request', [App\Http\Controllers\RegionAccessController::class, 'create'])->name('region-access.create');
    Route::post('/region-access/request', [App\Http\Controllers\RegionAccessController::class, 'store'])->name('region-access.store');
    
    // Borrowing routes
    Route::get('/borrowings', [App\Http\Controllers\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [App\Http\Controllers\BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/return', [App\Http\Controllers\BorrowingController::class, 'returnPage'])->name('borrowings.return.page');
    
    // Hide/Unhide borrowing history (User, Petugas, Admin bisa hide history mereka sendiri)
    Route::delete('/borrowings/{borrowing}/hide', [App\Http\Controllers\BorrowingController::class, 'hideHistory'])->name('borrowings.hide');
    Route::patch('/borrowings/{borrowing}/unhide', [App\Http\Controllers\BorrowingController::class, 'unhideHistory'])->name('borrowings.unhide');
    
    // Permanent delete (Admin only)
    Route::delete('/borrowings/{borrowing}', [App\Http\Controllers\BorrowingController::class, 'destroy'])->name('borrowings.destroy')->middleware('role:admin');
    
    // Borrowing approval routes (Admin and Petugas only)
    Route::patch('/borrowings/{borrowing}/approve', [App\Http\Controllers\BorrowingController::class, 'approve'])->name('borrowings.approve')->middleware('role:admin,petugas');
    Route::patch('/borrowings/{borrowing}/reject', [App\Http\Controllers\BorrowingController::class, 'reject'])->name('borrowings.reject')->middleware('role:admin,petugas');
    Route::patch('/borrowings/{borrowing}/approve-return', [App\Http\Controllers\BorrowingController::class, 'approveReturn'])->name('borrowings.approve.return')->middleware('role:admin,petugas');
    
    Route::patch('/borrowings/{borrowing}/return', [App\Http\Controllers\BorrowingController::class, 'return'])->name('borrowings.return');
    Route::get('/borrowings/{borrowing}', [App\Http\Controllers\BorrowingController::class, 'show'])->name('borrowings.show');
    Route::get('/borrowings/{borrowing}/print', [App\Http\Controllers\BorrowingController::class, 'printReceipt'])->name('borrowings.print');
    
    // Collection routes
    Route::get('/collections', [App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [App\Http\Controllers\CollectionController::class, 'store'])->name('collections.store');
    Route::delete('/collections/{collection}', [App\Http\Controllers\CollectionController::class, 'destroy'])->name('collections.destroy');
    
    // Review routes
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Petugas Dashboard Routes
Route::middleware(['auth', 'verified', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [PetugasDashboardController::class, 'manageUsers'])->name('users');
    Route::get('/users/list', [PetugasDashboardController::class, 'viewUsersList'])->name('users.list');
});

// Reports Routes (Petugas and Admin only)
Route::middleware(['auth', 'verified', 'role:petugas,admin'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
    Route::post('/borrowing', [App\Http\Controllers\ReportController::class, 'borrowingReport'])->name('borrowing');
    Route::post('/book', [App\Http\Controllers\ReportController::class, 'bookReport'])->name('book');
    Route::post('/user', [App\Http\Controllers\ReportController::class, 'userReport'])->name('user');
    Route::post('/return', [App\Http\Controllers\ReportController::class, 'returnReport'])->name('return');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'manageUsers'])->name('users');
    Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.update.role');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

    // Petugas Management Routes
    Route::get('/petugas', [App\Http\Controllers\PetugasController::class, 'index'])->name('petugas.index');
    Route::post('/petugas', [App\Http\Controllers\PetugasController::class, 'store'])->name('petugas.store');
    Route::put('/petugas/{petugas}', [App\Http\Controllers\PetugasController::class, 'update'])->name('petugas.update');
    Route::delete('/petugas/{petugas}', [App\Http\Controllers\PetugasController::class, 'destroy'])->name('petugas.destroy');
});

// User Approval (Admin & Petugas)
Route::middleware(['auth', 'verified', 'role:admin,petugas'])->prefix('user-approvals')->name('user-approvals.')->group(function () {
    Route::get('/', [App\Http\Controllers\UserApprovalController::class, 'index'])->name('index');
    Route::patch('/{user}/approve', [App\Http\Controllers\UserApprovalController::class, 'approve'])->name('approve');
    Route::patch('/{user}/reject', [App\Http\Controllers\UserApprovalController::class, 'reject'])->name('reject');
});

// Region Access Management (Admin & Petugas)
Route::middleware(['auth', 'verified', 'role:admin,petugas'])->prefix('region-access')->name('region-access.')->group(function () {
    Route::get('/', [App\Http\Controllers\RegionAccessController::class, 'index'])->name('index');
    Route::patch('/{regionAccess}/approve', [App\Http\Controllers\RegionAccessController::class, 'approve'])->name('approve');
    Route::patch('/{regionAccess}/reject', [App\Http\Controllers\RegionAccessController::class, 'reject'])->name('reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

require __DIR__.'/auth.php';
