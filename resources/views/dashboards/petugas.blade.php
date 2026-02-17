<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <x-sidebar-petugas />

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Staff Dashboard</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-700 font-semibold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 lg:p-8">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Welcome, {{ $user->name }}!</h1>
                    <p class="text-slate-600">Library Staff Dashboard</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Total Books -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_books'] }}</p>
                        <p class="text-sm text-slate-600">Total Books</p>
                        <p class="text-xs text-green-600 mt-2">{{ $stats['available_books'] }} available</p>
                    </div>

                    <!-- Active Borrowings -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['active_borrowings'] }}</p>
                        <p class="text-sm text-slate-600">Currently Borrowed</p>
                        @if($stats['overdue_borrowings'] > 0)
                            <p class="text-xs text-red-600 mt-2">{{ $stats['overdue_borrowings'] }} overdue</p>
                        @endif
                    </div>

                    <!-- Total Users -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_users'] }}</p>
                        <p class="text-sm text-slate-600">Total Students</p>
                        <p class="text-xs text-blue-600 mt-2">{{ $stats['books_added_by_me'] }} books added</p>
                    </div>
                </div>

                <!-- CTA Return Books -->
                @if($myActiveBorrowings > 0)
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800 mb-1">You Have Borrowed Books</h3>
                                    <p class="text-sm text-slate-600">You have <span class="font-semibold text-green-700">{{ $myActiveBorrowings }} book{{ $myActiveBorrowings > 1 ? 's' : '' }}</span> currently borrowed. Don't forget to return them on time!</p>
                                </div>
                            </div>
                            <a href="{{ route('borrowings.return.page') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shrink-0">
                                Return Books
                            </a>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Borrowings -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Recent Borrowings Card -->
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-slate-800">Recent Borrowings</h2>
                                <a href="{{ route('borrowings.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                    View All →
                                </a>
                            </div>

                            @if($recentBorrowings->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-slate-500">No borrowings yet</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($recentBorrowings->take(5) as $borrowing)
                                        <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl hover:border-purple-300 transition">
                                            <div class="w-12 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                                @if($borrowing->book->cover_image)
                                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover rounded-lg">
                                                @else
                                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-slate-800 truncate">{{ $borrowing->book->title }}</h3>
                                                <p class="text-sm text-slate-600">{{ $borrowing->user->name }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs px-2 py-1 rounded-full {{ $borrowing->status === 'borrowed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ $borrowing->status === 'borrowed' ? 'Borrowed' : 'Returned' }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">{{ $borrowing->borrowed_date->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Overdue Borrowings -->
                        @if($overdueBorrowings->isNotEmpty())
                            <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h2 class="text-xl font-bold text-red-800">Overdue Borrowings</h2>
                                </div>
                                <div class="space-y-3">
                                    @foreach($overdueBorrowings as $borrowing)
                                        <div class="bg-white p-4 rounded-xl">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $borrowing->book->title }}</p>
                                                    <p class="text-sm text-slate-600">{{ $borrowing->user->name }}</p>
                                                    <p class="text-xs text-red-600 font-medium mt-1">
                                                        Overdue {{ $borrowing->getDaysOverdue() }} day{{ $borrowing->getDaysOverdue() > 1 ? 's' : '' }}
                                                    </p>
                                                </div>
                                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                                                        Return
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <h2 class="text-xl font-bold text-slate-800 mb-4">Aksi Cepat</h2>
                            <div class="space-y-3">
                                <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Tambah Buku</p>
                                        <p class="text-xs text-slate-600">Tambah buku baru</p>
                                    </div>
                                </a>

                                <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Kelola Peminjaman</p>
                                        <p class="text-xs text-slate-600">Lihat semua peminjaman</p>
                                    </div>
                                </a>

                                <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Lihat Koleksi</p>
                                        <p class="text-xs text-slate-600">Jelajahi buku</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Low Stock Alert -->
                        @if($lowStockBooks->isNotEmpty())
                            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <h3 class="font-bold text-yellow-800">Stok Menipis</h3>
                                </div>
                                <div class="space-y-2">
                                    @foreach($lowStockBooks as $book)
                                        <div class="bg-white p-3 rounded-lg">
                                            <p class="font-semibold text-slate-800 text-sm">{{ $book->title }}</p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                Tersisa {{ $book->available_copies }} dari {{ $book->total_copies }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
