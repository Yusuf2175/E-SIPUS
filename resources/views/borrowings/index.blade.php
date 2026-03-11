@extends('layouts.dashboard')

@section('page-title', 'Borrowing Management')

@section('content')
<div class="flex-1 -mt-10">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">
                        {{ Auth::user()->role === 'user' ? 'Borrowing History' : 'Borrowing Records' }}
                    </h1>
                    <p class="text-slate-600 mt-1">
                        {{ Auth::user()->role === 'user' ? 'Track your borrowed books and return status' : 'Monitor and manage all library borrowing transactions' }}
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- CTA Return Books (for User) -->
        @if(Auth::user()->role === 'user')
            @php
                $activeBorrowingsCount = \App\Models\Borrowing::where('user_id', Auth::id())
                    ->whereIn('status', ['approved', 'borrowed'])
                    ->count();
            @endphp
            
            @if($activeBorrowingsCount > 0)
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
                                <p class="text-white/90 text-sm">You have <span class="font-bold">{{ $activeBorrowingsCount }} book{{ $activeBorrowingsCount > 1 ? 's' : '' }}</span> currently borrowed. Don't forget to return them on time!</p>
                            </div>
                        </div>
                        <a href="{{ route('borrowings.return.page') }}" class="bg-white hover:bg-slate-50 text-red-600 font-semibold py-3 px-6 rounded-xl transition-all shrink-0">
                            Return Books Now
                        </a>
                    </div>
                </div>
            @endif
        @endif

        <!-- Statistics Cards (for Admin/Petugas) -->
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Currently Borrowed Card -->
                <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 mb-2">Currently Borrowed</p>
                            <p class="text-4xl font-bold text-slate-900">{{ $stats['active'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Books Returned Card -->
                <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 mb-2">Books Returned</p>
                            <p class="text-4xl font-bold text-slate-900">{{ $stats['returned'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Overdue Books Card -->
                <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 mb-2">Overdue Books</p>
                            <p class="text-4xl font-bold text-slate-900">{{ $stats['overdue'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Borrowings Card -->
                <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 mb-2">Total Borrowings</p>
                            <p class="text-4xl font-bold text-slate-900">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 mb-6 overflow-hidden">
            <div class="border-b-2 border-slate-100">
                <nav class="flex -mb-px overflow-x-auto">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ !request('status') ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        All Records
                    </a>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
                        <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'pending' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            <span class="flex items-center gap-2">
                                Pending Requests
                                @if(isset($stats['pending']) && $stats['pending'] > 0)
                                    <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
                                @endif
                            </span>
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'approved']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'approved' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            Approved
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'pending_return']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'pending_return' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            <span class="flex items-center gap-2">
                                Pending Returns
                                @if(isset($stats['pending_return']) && $stats['pending_return'] > 0)
                                    <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['pending_return'] }}</span>
                                @endif
                            </span>
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'rejected']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'rejected' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            Rejected
                        </a>
                    @endif
                    <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'borrowed' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Currently Borrowed
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'returned' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Returned
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" class="px-6 py-4 text-sm font-semibold transition whitespace-nowrap {{ request('status') == 'overdue' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Overdue
                    </a>
                </nav>
            </div>
        </div>

        <!-- Empty State -->
        @if($borrowings->isEmpty())
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-12 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Borrowings Yet</h3>
                <p class="text-slate-600 mb-6">{{ Auth::user()->role === 'user' ? 'Start borrowing your favorite books from our collection' : 'No borrowing data available for this filter' }}</p>
                @if(Auth::user()->role === 'user')
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-ungu text-white font-semibold rounded-xl hover:bg-primarys transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse Books
                    </a>
                @endif
            </div>
        @else
            <!-- Borrowings List - Clean & Organized Layout -->
            <div class="space-y-6">
                @foreach($borrowings as $borrowing)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-200">
                        <!-- Book Info & Status Section -->
                        <div class="p-6">
                            <div class="flex items-start gap-5">
                                <!-- Book Cover -->
                                <div class="flex-shrink-0">
                                    <div class="w-28 h-40 rounded-xl overflow-hidden shadow-md ring-2 ring-slate-100">
                                        @if($borrowing->book->cover_image)
                                            <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Book Details & User Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2 line-clamp-2">
                                                {{ $borrowing->book->title }}
                                            </h3>
                                            <p class="text-sm text-slate-600 mb-3">
                                                <span class="font-semibold">by</span> {{ $borrowing->book->author }}
                                            </p>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="flex-shrink-0">
                                            @if($borrowing->status === 'pending')
                                                <span class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    PENDING
                                                </span>
                                            @elseif($borrowing->status === 'approved')
                                                <span class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    BORROWED
                                                </span>
                                            @elseif($borrowing->status === 'pending_return')
                                                <span class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                    </svg>
                                                    PENDING RETURN
                                                </span>
                                            @elseif($borrowing->status === 'rejected')
                                                <span class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    REJECTED
                                                </span>
                                            @elseif($borrowing->status === 'borrowed')
                                                @if($borrowing->isOverdue())
                                                    <span class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        OVERDUE
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        BORROWED
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    RETURNED
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if(Auth::user()->role !== 'user')
                                        <!-- Borrower Info (Admin/Petugas View) -->
                                        <div class="inline-flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-2.5 border border-slate-200 mb-4">
                                            <div class="w-10 h-10 bg-ungu rounded-lg flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($borrowing->user->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-slate-900 text-sm truncate">{{ $borrowing->user->name }}</p>
                                                <p class="text-xs text-slate-600 truncate">{{ $borrowing->user->email }}</p>
                                            </div>
                                            @if($borrowing->user->role === 'admin')
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-700">Admin</span>
                                            @elseif($borrowing->user->role === 'petugas')
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-green-100 text-green-700">Staff</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-100 text-blue-700">User</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Date Timeline -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <!-- Borrowed Date -->
                                        <div class="flex-1 bg-blue-50 rounded-xl p-3 border border-blue-100">
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs font-semibold text-blue-700">Borrowed</span>
                                            </div>
                                            <p class="text-sm font-bold text-blue-900">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</p>
                                        </div>

                                        <!-- Due Date -->
                                        <div class="flex-1 bg-orange-50 rounded-xl p-3 border border-orange-100">
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xs font-semibold text-orange-700">Due Date</span>
                                            </div>
                                            <p class="text-sm font-bold text-orange-900">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}</p>
                                        </div>

                                        <!-- Returned Date (if returned) -->
                                        @if($borrowing->status === 'returned' && $borrowing->returned_date)
                                            <div class="flex-1 bg-green-50 rounded-xl p-3 border border-green-100">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-green-700">Returned</span>
                                                </div>
                                                <p class="text-sm font-bold text-green-900">{{ \Carbon\Carbon::parse($borrowing->returned_date)->format('d M Y') }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Additional Info (Notes/Rejection Reason) -->
                                    @if($borrowing->notes || $borrowing->reject_reason || ($borrowing->status === 'approved' && $borrowing->approvedBy))
                                        <div class="space-y-2 mb-4">
                                            @if($borrowing->notes)
                                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <p class="text-xs font-semibold text-blue-700 mb-1">Borrower Notes:</p>
                                                    <p class="text-sm text-blue-900">{{ $borrowing->notes }}</p>
                                                </div>
                                            @endif

                                            @if($borrowing->status === 'rejected' && $borrowing->reject_reason)
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                    <p class="text-xs font-semibold text-red-700 mb-1">Rejection Reason:</p>
                                                    <p class="text-sm text-red-900">{{ $borrowing->reject_reason }}</p>
                                                    @if($borrowing->approvedBy)
                                                        <p class="text-xs text-red-600 mt-1">Rejected by: {{ $borrowing->approvedBy->name }}</p>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($borrowing->status === 'approved' && $borrowing->approvedBy)
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                    <p class="text-sm text-green-900">
                                                        Approved by <span class="font-semibold">{{ $borrowing->approvedBy->name }}</span> 
                                                        on {{ $borrowing->approved_at->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Section (Separated) -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <!-- Left: View Book Button -->
                                <a href="{{ route('books.show', $borrowing->book) }}" class="inline-flex items-center px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all border border-slate-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Book
                                </a>

                                <!-- Right: Action Buttons -->
                                <div class="flex items-center gap-3 flex-wrap">

                                @if(in_array($borrowing->status, ['approved', 'borrowed', 'returned']))
                                    <a href="{{ route('borrowings.print', $borrowing) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download Receipt
                                    </a>
                                @endif

                                @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && $borrowing->status === 'pending')
                                    <button type="button" onclick="confirmApprove({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Approve
                                    </button>

                                    <button onclick="openRejectModal({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                @endif

                                @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && $borrowing->status === 'pending_return')
                                    <button type="button" onclick="confirmApproveReturn({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Approve Return
                                    </button>
                                @endif

                                @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && ($borrowing->status === 'borrowed' || $borrowing->status === 'approved'))
                                    <button onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2.5 bg-ungu hover:bg-primarys text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                        Return Book
                                    </button>
                                @endif

                                @if($borrowing->status === 'returned' && $borrowing->user_id === Auth::id())
                                    <button onclick="confirmDeleteHistory({{ $borrowing->id }}, '{{ $borrowing->book->title }}')" class="inline-flex items-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                        Hide History
                                    </button>
                                @endif

                                @if(Auth::user()->role === 'admin' && $borrowing->status === 'returned')
                                    <button onclick="confirmPermanentDelete({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete Permanently
                                    </button>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-8 flex justify-end">
                {{ $borrowings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<x-borrowing-alert />

@endsection
