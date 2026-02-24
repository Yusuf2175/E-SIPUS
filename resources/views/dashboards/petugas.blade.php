@extends('layouts.dashboard')

@section('page-title', 'Staff Dashboard')

@section('content')
    <!-- Header -->
    <div class="mb-16">
        <div class="flex flex-col items-center ">
                <h1 class="text-3xl font-bold text-slate-800">Welcome back, {{ $user->name }}!</h1>
                <p class="text-slate-600 mt-1">Library Staff Dashboard - Manage books and borrowings</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="bg-blue-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-blue-600">Active</span>
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

        <!-- Total Users -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="bg-green-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-green-600">Users</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_users'] }}</p>
            <p class="text-sm font-medium text-slate-600 mb-4">Total Students</p>
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600">Books You Added</span>
                    <span class="font-semibold text-ungu">{{ $stats['books_added_by_me'] }}</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Borrowings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Borrowings Card -->
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

            <!-- Overdue Borrowings -->
            @if($overdueBorrowings->isNotEmpty())
                <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-red-800">Overdue Borrowings</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($overdueBorrowings as $borrowing)
                            <div class="bg-white border border-red-200 p-4 rounded-xl">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-800 truncate">{{ $borrowing->book->title }}</p>
                                        <p class="text-sm text-slate-600 truncate">{{ $borrowing->user->name }}</p>
                                        <p class="text-xs text-red-600 font-semibold mt-1">
                                            Overdue {{ $borrowing->getDaysOverdue() }} day{{ $borrowing->getDaysOverdue() > 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition flex-shrink-0">
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
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Quick Actions</h2>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-4 bg-cstm border border-primarys/20 rounded-xl hover:bg-primarys/10 transition group">
                        <div class="w-10 h-10 bg-ungu rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Add New Book</p>
                            <p class="text-xs text-slate-600">Add book to library</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-primarys transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Manage Borrowings</p>
                            <p class="text-xs text-slate-600">View all borrowings</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('petugas.users') }}" class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition group">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Manage Borrowings Students</p>
                            <p class="text-xs text-slate-600">View all Borrowings students</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition group">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Browse Collection</p>
                            <p class="text-xs text-slate-600">Explore all books</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Low Stock Alert -->
            @if($lowStockBooks->isNotEmpty())
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-yellow-800">Low Stock Alert</h3>
                    </div>
                    <div class="space-y-2">
                        @foreach($lowStockBooks as $book)
                            <div class="bg-white border border-yellow-200 p-3 rounded-xl">
                                <p class="font-semibold text-slate-800 text-sm truncate">{{ $book->title }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-xs text-yellow-700 font-medium">
                                        {{ $book->available_copies }} of {{ $book->total_copies }} left
                                    </p>
                                    <div class="w-16 bg-yellow-100 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($book->available_copies / $book->total_copies) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
