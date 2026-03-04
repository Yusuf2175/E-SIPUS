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
                        {{ Auth::user()->isUser() ? 'Borrowing History' : 'Borrowing Records' }}
                    </h1>
                    <p class="text-slate-600 mt-1">
                        {{ Auth::user()->isUser() ? 'Track your borrowed books and return status' : 'Monitor and manage all library borrowing transactions' }}
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
        @if(Auth::user()->isUser())
            @php
                $activeBorrowingsCount = \App\Models\Borrowing::where('user_id', Auth::id())
                    ->where('status', 'borrowed')
                    ->where('approval_status', 'approved')
                    ->count();
                $pendingBorrowingsCount = \App\Models\Borrowing::where('user_id', Auth::id())
                    ->where('approval_status', 'pending')
                    ->count();
            @endphp
            
            @if($pendingBorrowingsCount > 0)
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl p-6 mb-8 text-white">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1">Pending Approval</h3>
                                <p class="text-white/90 text-sm">You have <span class="font-bold">{{ $pendingBorrowingsCount }} borrowing request{{ $pendingBorrowingsCount > 1 ? 's' : '' }}</span> waiting for admin/staff approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
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
                            Request Return
                        </a>
                    </div>
                </div>
            @endif
        @endif

        <!-- Statistics Cards (for Admin/Petugas) -->
        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
            @php
                $pendingApprovalCount = \App\Models\Borrowing::where('approval_status', 'pending')->count();
                $pendingReturnCount = \App\Models\Borrowing::where('return_approval_status', 'pending')->count();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-yellow-50 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-yellow-600">Pending</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $pendingApprovalCount }}</p>
                    <p class="text-sm font-medium text-slate-600">Pending Approval</p>
                </div>

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
                        <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                            </svg>
                        </div>
                        <div class="bg-purple-50 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-purple-600">Return</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-slate-800 mb-2">{{ $pendingReturnCount }}</p>
                    <p class="text-sm font-medium text-slate-600">Pending Return</p>
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
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 mb-6 overflow-hidden">
            <div class="border-b-2 border-slate-100">
                <nav class="flex -mb-px flex-wrap">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-4 text-sm font-semibold transition {{ !request('status') ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        All Records
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                        <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'pending' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            Pending Approval
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'pending_return']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'pending_return' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                            Pending Return
                        </a>
                    @endif
                    <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'borrowed' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Currently Borrowed
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'returned' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Returned
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'rejected']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'rejected' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                        Rejected
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
                <p class="text-slate-600 mb-6">{{ Auth::user()->isUser() ? 'Start borrowing your favorite books from our collection' : 'No borrowing data available for this filter' }}</p>
                @if(Auth::user()->isUser())
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-ungu text-white font-semibold rounded-xl hover:bg-primarys transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Browse Books
                    </a>
                @endif
            </div>
        @else
