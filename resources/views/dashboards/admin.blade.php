@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class=" mb-16">
        <div class="flex  flex-col items-center mb-3">
                <h1 class="text-3xl font-bold text-slate-800">Welcome back, {{ $user->name }}!</h1>
                <p class="text-slate-600 mt-1">Administrator Dashboard - Manage the entire library system</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="bg-blue-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-blue-600">Users</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_users'] }}</p>
            <p class="text-sm font-medium text-slate-600 mb-4">Total Registered Users</p>
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Regular Users</span>
                    <span class="font-semibold text-blue-600">{{ $stats['total_regular_users'] }}</span>
                </div>
            </div>
        </div>

        <!-- Total Books -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-ungu rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="bg-cstm px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-primarys">Books</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_books'] }}</p>
            <p class="text-sm font-medium text-slate-600 mb-4">Books in Library</p>
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Available Now</span>
                    <span class="font-semibold text-green-600">{{ $stats['available_books'] }}</span>
                </div>
            </div>
        </div>

        <!-- Active Borrowings -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="bg-green-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-green-600">Active</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['active_borrowings'] }}</p>
            <p class="text-sm font-medium text-slate-600 mb-4">Currently Borrowed</p>
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Overdue Books</span>
                    @if($stats['overdue_borrowings'] > 0)
                        <span class="font-semibold text-red-600">{{ $stats['overdue_borrowings'] }}</span>
                    @else
                        <span class="font-semibold text-green-600">0</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Return Books -->
    @if($myActiveBorrowings > 0)
        <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-1">You Have Borrowed Books</h3>
                        <p class="text-white/90 text-sm">You have <span class="font-bold">{{ $myActiveBorrowings }} book{{ $myActiveBorrowings > 1 ? 's' : '' }}</span> currently borrowed. Don't forget to return them on time!</p>
                    </div>
                </div>
                <a href="{{ route('borrowings.return.page') }}" class="bg-white hover:bg-slate-50 text-red-600 font-semibold py-3 px-6 rounded-xl transition-all shrink-0">
                    Return Books Now
                </a>
            </div>
        </div>
    @endif

    <!-- Staff Statistics & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Borrowings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Recent Borrowings</h2>
                    </div>
                    <a href="{{ route('borrowings.index') }}" class="text-ungu hover:text-primarys text-sm font-semibold flex items-center gap-1 transition">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                @if($recentBorrowings->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">No borrowings yet</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentBorrowings as $borrowing)
                            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl hover:border-ungu hover:bg-slate-50 transition">
                                <div class="w-12 h-16 bg-gradient-to-br from-ungu to-primarys rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($borrowing->book->cover_image)
                                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-800 truncate">{{ $borrowing->book->title }}</h3>
                                    <p class="text-sm text-slate-600 truncate">{{ $borrowing->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $borrowing->status === 'borrowed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $borrowing->status === 'borrowed' ? 'Borrowed' : 'Returned' }}
                                        </span>
                                        <span class="text-xs text-slate-500">{{ $borrowing->borrowed_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($recentBorrowings->hasPages())
                        <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                            <div class="text-sm text-slate-600">
                                Showing {{ $recentBorrowings->firstItem() }} to {{ $recentBorrowings->lastItem() }} of {{ $recentBorrowings->total() }} borrowings
                            </div>
                            <div class="flex items-center gap-2">
                                @if($recentBorrowings->onFirstPage())
                                    <span class="px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $recentBorrowings->previousPageUrl() }}" class="px-3 py-2 text-ungu bg-cstm hover:bg-primarys hover:text-white rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </a>
                                @endif

                                <div class="flex items-center gap-1">
                                    @foreach($recentBorrowings->getUrlRange(1, $recentBorrowings->lastPage()) as $page => $url)
                                        @if($page == $recentBorrowings->currentPage())
                                            <span class="px-3 py-2 bg-ungu text-white rounded-lg font-semibold">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition">{{ $page }}</a>
                                        @endif
                                    @endforeach
                                </div>

                                @if($recentBorrowings->hasMorePages())
                                    <a href="{{ $recentBorrowings->nextPageUrl() }}" class="px-3 py-2 text-ungu bg-cstm hover:bg-primarys hover:text-white rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Staff Statistics & Quick Actions -->
        <div class="space-y-6">
            <!-- Staff Statistics -->
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Staff Overview</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-ungu rounded-xl text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Administrator</p>
                                <p class="text-xs text-white/80">Full system access</p>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ $stats['total_admins'] }}</p>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Petugas</p>
                                <p class="text-xs text-slate-600">Library staff members</p>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['total_petugas'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Quick Actions</h3>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Manage Users</p>
                            <p class="text-xs text-slate-600">View and manage all users</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-4 bg-cstm border border-primarys/20 rounded-xl hover:bg-primarys/10 transition group">
                        <div class="w-10 h-10 bg-ungu rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Manage Books</p>
                            <p class="text-xs text-slate-600">Add, edit, or remove books</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-primarys transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition group">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Manage Borrowings</p>
                            <p class="text-xs text-slate-600">Track all book borrowings</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Recent Users</h3>
            </div>
            <a href="{{ route('admin.users') }}" class="text-ungu hover:text-primarys text-sm font-semibold flex items-center gap-1 transition">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        @if($recentUsers->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium">No users registered yet</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($recentUsers as $recentUser)
                    <div class="flex items-center gap-3 p-4 border border-slate-200 rounded-xl hover:border-ungu hover:bg-slate-50 transition">
                        <div class="w-12 h-12 bg-gradient-to-br from-ungu to-primarys rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($recentUser->name, 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 truncate">{{ $recentUser->name }}</p>
                            <p class="text-xs text-slate-600 truncate">{{ $recentUser->email }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold flex-shrink-0 {{ $recentUser->role === 'admin' ? 'bg-red-100 text-red-700' : ($recentUser->role === 'petugas' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($recentUser->role) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($recentUsers->hasPages())
                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                    <div class="text-sm text-slate-600">
                        Showing {{ $recentUsers->firstItem() }} to {{ $recentUsers->lastItem() }} of {{ $recentUsers->total() }} users
                    </div>
                    <div class="flex items-center gap-2">
                        @if($recentUsers->onFirstPage())
                            <span class="px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $recentUsers->previousPageUrl() }}" class="px-3 py-2 text-ungu bg-cstm hover:bg-primarys hover:text-white rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        <div class="flex items-center gap-1">
                            @foreach($recentUsers->getUrlRange(1, $recentUsers->lastPage()) as $page => $url)
                                @if($page == $recentUsers->currentPage())
                                    <span class="px-3 py-2 bg-ungu text-white rounded-lg font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($recentUsers->hasMorePages())
                            <a href="{{ $recentUsers->nextPageUrl() }}" class="px-3 py-2 text-ungu bg-cstm hover:bg-primarys hover:text-white rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
