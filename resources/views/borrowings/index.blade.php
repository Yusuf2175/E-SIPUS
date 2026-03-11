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
                    ->whereIn('status', ['approved', 'borrowed']) // Status approved = sedang dipinjam
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
                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="bg-blue-50 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-blue-600">Active</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['active'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-slate-600">Currently Borrowed</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-green-500 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-green-50 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-green-600">Returned</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['returned'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-slate-600">Books Returned</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-red-500 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-red-50 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-red-600">Overdue</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['overdue'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-slate-600">Overdue Books</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-ungu rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="bg-cstm px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-primarys">Total</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-slate-600">Total Borrowings</p>
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
            <!-- Borrowings List -->
            <div class="space-y-5">
                @foreach($borrowings as $borrowing)
                    <div class="group relative">
                        <div class="relative bg-white rounded-3xl shadow-md transition-all duration-300 overflow-hidden hover:shadow-xl">
                            <div class="p-6">
                                <!-- Header Section -->
                                <div class="flex items-start justify-between mb-6">
                                    <!-- Book Cover & Title -->
                                    <div class="flex gap-5 flex-1">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-24 h-36 rounded-2xl overflow-hidden shadow-lg transform group-hover:scale-105 transition-all duration-300 ring-2 ring-slate-200">
                                                @if($borrowing->book->cover_image)
                                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Book Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-xl font-bold text-slate-900 line-clamp-2 leading-tight mb-2">
                                                {{ $borrowing->book->title }}
                                            </h3>
                                            <div class="flex items-center gap-2 text-slate-600 mb-4">
                                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold">{{ $borrowing->book->author }}</span>
                                            </div>

                                            @if(Auth::user()->role !== 'user')
                                                <!-- Borrower Info -->
                                                <div class="inline-flex items-center gap-3 bg-gradient-to-r from-slate-100 to-slate-50 rounded-xl px-4 py-2 border border-slate-200">
                                                    <div class="w-10 h-10 bg-ungu rounded-lg flex items-center justify-center">
                                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($borrowing->user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ $borrowing->user->name }}</p>
                                                        <p class="text-xs text-slate-600 truncate">{{ $borrowing->user->email }}</p>
                                                    </div>
                                                    @if($borrowing->user->role === 'admin')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-red-100 text-red-700">Admin</span>
                                                    @elseif($borrowing->user->role === 'petugas')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-700">Staff</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700">User</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex-shrink-0">
                                        @if($borrowing->status === 'pending')
                                            <div class="bg-yellow-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold tracking-wide">PENDING</span>
                                                </div>
                                            </div>
                                        @elseif($borrowing->status === 'approved')
                                            <div class="bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold tracking-wide">BORROWED</span>
                                                </div>
                                            </div>
                                        @elseif($borrowing->status === 'pending_return')
                                            <div class="bg-orange-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold tracking-wide">PENDING RETURN</span>
                                                </div>
                                            </div>
                                        @elseif($borrowing->status === 'rejected')
                                            <div class="bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold tracking-wide">REJECTED</span>
                                                </div>
                                            </div>
                                        @elseif($borrowing->status === 'borrowed')
                                            @if($borrowing->isOverdue())
                                                <div class="bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-xs font-bold tracking-wide">OVERDUE</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        <span class="text-xs font-bold tracking-wide">BORROWED</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-blue-500 text-white px-4 py-2 rounded-xl shadow-lg">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-xs font-bold tracking-wide">RETURNED</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

                                <!-- Date Timeline & Actions -->
                                <div class="flex items-center justify-between gap-6 flex-wrap">
                                    <!-- Date Timeline -->
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <!-- Borrowed Date -->
                                        <div class="flex-1 bg-blue-50 rounded-xl p-3 border border-blue-200">
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs font-semibold text-blue-700">Borrowed</span>
                                            </div>
                                            <p class="text-sm font-bold text-blue-900">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</p>
                                        </div>

                                        <!-- Due Date -->
                                        <div class="flex-1 bg-orange-50 rounded-xl p-3 border border-orange-200">
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
                                            <div class="flex-1 bg-green-50 rounded-xl p-3 border border-green-200">
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

                                    <!-- Additional Info for Pending/Rejected Status -->
                                    @if($borrowing->status === 'pending' || $borrowing->status === 'rejected' || $borrowing->notes || $borrowing->reject_reason)
                                        <div class="mt-4 space-y-3">
                                            @if($borrowing->notes)
                                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-semibold text-blue-700 mb-1">Borrower Notes:</p>
                                                            <p class="text-sm text-blue-900">{{ $borrowing->notes }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($borrowing->status === 'rejected' && $borrowing->reject_reason)
                                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-semibold text-red-700 mb-1">Rejection Reason:</p>
                                                            <p class="text-sm text-red-900">{{ $borrowing->reject_reason }}</p>
                                                            @if($borrowing->approvedBy)
                                                                <p class="text-xs text-red-600 mt-2">Rejected by: {{ $borrowing->approvedBy->name }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($borrowing->status === 'approved' && $borrowing->approvedBy)
                                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <p class="text-sm text-green-900">
                                                            Approved by <span class="font-semibold">{{ $borrowing->approvedBy->name }}</span> 
                                                            on {{ $borrowing->approved_at->format('d M Y, H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <a href="{{ route('books.show', $borrowing->book) }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Book
                                        </a>

                                        @if(in_array($borrowing->status, ['approved', 'borrowed', 'returned']))
                                            <!-- Print Receipt Button -->
                                            <a href="{{ route('borrowings.print', $borrowing) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download Bukti
                                            </a>
                                        @endif

                                        @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && $borrowing->status === 'pending')
                                            <!-- Approve Button -->
                                            <button type="button" onclick="confirmApprove({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Approve
                                            </button>

                                            <!-- Reject Button -->
                                            <button onclick="openRejectModal({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject
                                            </button>
                                        @endif

                                        @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && $borrowing->status === 'pending_return')
                                            <!-- Approve Return Button -->
                                            <button type="button" onclick="confirmApproveReturn({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Approve Return
                                            </button>
                                        @endif

                                        @if((Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') && ($borrowing->status === 'borrowed' || $borrowing->status === 'approved'))
                                            <button onclick="openReturnModal({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2 bg-ungu hover:bg-primarys text-white font-semibold rounded-xl transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                                Return Book
                                            </button>
                                        @endif

                                        @if($borrowing->status === 'returned' && $borrowing->user_id === Auth::id())
                                            <!-- Delete History Button (User/Petugas/Admin bisa hide history mereka sendiri) -->
                                            <button onclick="confirmDeleteHistory({{ $borrowing->id }}, '{{ $borrowing->book->title }}')" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                                Hide History
                                            </button>
                                        @endif

                                        @if(Auth::user()->role === 'admin' && $borrowing->status === 'returned')
                                            <!-- Permanent Delete Button (Admin Only) -->
                                            <button onclick="confirmPermanentDelete({{ $borrowing->id }}, '{{ $borrowing->book->title }}', '{{ $borrowing->user->name }}')" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
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
