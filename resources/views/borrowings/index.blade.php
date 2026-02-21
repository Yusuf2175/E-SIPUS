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
                @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                        <nav class="flex -mb-px">
                            <a href="{{ route('borrowings.index') }}" class="px-6 py-4 text-sm font-semibold transition {{ !request('status') ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                                All Records
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'borrowed' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                                Currently Borrowed
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'returned' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                                Returned
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" class="px-6 py-4 text-sm font-semibold transition {{ request('status') == 'overdue' ? 'border-b-2 border-ungu text-ungu bg-cstm/30' : 'text-slate-600 hover:text-ungu hover:bg-slate-50' }}">
                                Overdue
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Borrowings List -->
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
                    <div class="space-y-5">
                        @foreach($borrowings as $borrowing)
                            <div class="group relative">
                                <div class="relative bg-white rounded-3xl shadow-md transition-all duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <!-- Header Section with Status -->
                                        <div class="flex items-start justify-between mb-6">
                                            <!-- Book Cover & Title -->
                                            <div class="flex gap-5 flex-1">
                                                <div class="relative flex-shrink-0">
                                                    <div class="w-24 h-36 rounded-2xl overflow-hidden shadow-2xl transform group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 ring-4 ring-white">
                                                        @if($borrowing->book->cover_image)
                                                            <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover">
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Book Info -->
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-xl font-black text-slate-900   line-clamp-2 leading-tight mb-2">
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

                                                    @if(!Auth::user()->isUser())
                                                        <!-- Modern Borrower Badge -->
                                                        <div class="inline-flex items-center gap-3 bg-gradient-to-r from-slate-100 via-slate-200 to-slate-100 rounded-2xl px-4 py-3 border-2 border-slate-200/70 shadow-sm">
                                                            <div class="relative">
                                                                <div class="w-12 h-12 bg-ungu rounded-xl flex items-center justify-center shadow-md">
                                                                    <span class="text-white font-black text-sm">{{ strtoupper(substr($borrowing->user->name, 0, 1)) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="font-bold text-slate-900 text-sm truncate">{{ $borrowing->user->name }}</p>
                                                                <p class="text-xs text-slate-600 truncate">{{ $borrowing->user->email }}</p>
                                                            </div>
                                                            @if($borrowing->user->role === 'admin')
                                                                <span class="px-3 py-1 text-sm  rounded-xl bg-gradient-to-r from-red-500 to-pink-500 text-white ">
                                                                    admin
                                                                </span>
                                                            @elseif($borrowing->user->role === 'petugas')
                                                                <span class="px-3 py-1 text-sm  rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white ">
                                                                    staf
                                                                </span>
                                                            @else
                                                                <span class="px-3 py-1 text-sm  rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 text-white ">
                                                                    user
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Status Pill -->
                                            <div class="flex-shrink-0">
                                                @if($borrowing->status === 'borrowed')
                                                    @if($borrowing->isOverdue())
                                                        <div class="relative">
                                                            <div class="absolute inset-0 bg-red-500 rounded-2xl "></div>
                                                                <div class="flex items-center ">
                                                                    <span class="text-xs font-black">Overdue</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="relative">
                                                            <div class="relative bg-green-500/70 text-white px-5 py-3 rounded-2xl ">
                                                                <div class="flex items-center ">
                                                                    <span class="text-xs font-black ">Borrowed</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="relative">
                                                        <div class="absolute inset-0 bg-green-500 rounded-2xl blur-md opacity-50"></div>
                                                        <div class="relative bg-gradient-to-br from-green-500 to-green-600 text-white px-5 py-3 rounded-2xl shadow-xl">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                <span class="text-xs font-black tracking-widest">RETURNED</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="my-6 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

                                        <!-- Bottom Section: Timeline & Actions -->
                                        <div class="flex items-center justify-between gap-6">
                                            <!-- Date Timeline Cards -->
                                            <div class="flex items-center gap-4 flex-1">
                                                <!-- Borrowed Date Card -->
                                                <div class="flex-1 relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 border-2 border-blue-200/50 ">
                                                    <div class="relative flex items-center gap-3">
                                                        <div class="w-11 h-11 bg-blue-500 rounded-xl flex items-center justify-center ">
                                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-semibold text-blue-600 mb-0.5">Borrowed</p>
                                                            <p class="text-sm font-black text-slate-900">{{ $borrowing->borrowed_date->format('d M Y') }}</p>
                                                            <p class="text-xs text-slate-600 font-medium">{{ $borrowing->borrowed_date->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Arrow Connector -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Due Date Card -->
                                                <div class="flex-1 relative overflow-hidden rounded-2xl bg-gradient-to-br from-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-50 to-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-100/50 p-4 border-2 border-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-200/50 ">
                                                    <div class="absolute top-0 right-0 w-20 h-20 bg-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-500/10 rounded-full -mr-10 -mt-10"></div>
                                                    <div class="relative flex items-center gap-3">
                                                        <div class="w-11 h-11 bg-gradient-to-br from-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-500 to-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-600 rounded-xl flex items-center justify-center shadow-md group-hover/date:scale-110 transition-transform">
                                                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-semibold text-{{ $borrowing->isOverdue() ? 'red' : 'orange' }}-600 mb-0.5">Due Date</p>
                                                            <p class="text-sm font-black text-slate-900">{{ $borrowing->due_date->format('d M Y') }}</p>
                                                            @if($borrowing->isOverdue())
                                                                <p class="text-xs text-red-600 font-black">⚠ {{ $borrowing->getDaysOverdue() }} day{{ $borrowing->getDaysOverdue() > 1 ? 's' : '' }} late!</p>
                                                            @else
                                                                <p class="text-xs text-slate-600 font-medium">{{ $borrowing->due_date->diffForHumans() }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex gap-3">
                                                <a href="{{ route('books.show', $borrowing->book) }}" class="group/btn">
                                                    <div class="px-6 py-3 bg-ungu hover:bg-primarys text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            <span>View</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                
                                                @if($borrowing->status === 'borrowed' && (Auth::user()->isAdmin() || Auth::user()->isPetugas()))
                                                    <button type="button" onclick="openReturnModal({{ $borrowing->id }})" class="group/btn">
                                                        <div class="px-6 py-3 bg-red-400 hover:bg-red-500 text-white font-bold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                                                </svg>
                                                                <span>Return</span>
                                                            </div>
                                                        </div>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($borrowings->hasPages())
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-5 border-t-2 border-slate-200">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-bold text-slate-800">Showing {{ $borrowings->firstItem() }} - {{ $borrowings->lastItem() }}</p>
                                        <p class="text-slate-600">of {{ $borrowings->total() }} borrowings</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($borrowings->onFirstPage())
                                        <span class="px-4 py-2.5 text-slate-400 bg-slate-200 rounded-xl cursor-not-allowed flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ $borrowings->previousPageUrl() }}" class="px-4 py-2.5 text-white bg-gradient-to-r from-ungu to-primarys hover:from-primarys hover:to-secondrys rounded-xl transition-all flex items-center gap-2 font-bold shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Previous
                                        </a>
                                    @endif

                                    <div class="flex items-center gap-1">
                                        @foreach($borrowings->getUrlRange(1, $borrowings->lastPage()) as $page => $url)
                                            @if($page == $borrowings->currentPage())
                                                <span class="px-4 py-2.5 bg-gradient-to-r from-ungu to-primarys text-white rounded-xl font-bold shadow-lg">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}" class="px-4 py-2.5 text-slate-600 hover:bg-white hover:text-ungu rounded-xl transition-all font-semibold hover:shadow-md">{{ $page }}</a>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if($borrowings->hasMorePages())
                                        <a href="{{ $borrowings->nextPageUrl() }}" class="px-4 py-2.5 text-white bg-gradient-to-r from-ungu to-primarys hover:from-primarys hover:to-secondrys rounded-xl transition-all flex items-center gap-2 font-bold shadow-md hover:shadow-lg">
                                            Next
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="px-4 py-2.5 text-slate-400 bg-slate-200 rounded-xl cursor-not-allowed flex items-center gap-2 font-semibold">
                                            Next
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function openReturnModal(borrowingId) {
            Swal.fire({
                title: 'Book Return',
                html: createReturnForm(),
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Return Book',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                preConfirm: () => {
                    return validateAndGetFormData();
                }
            }).then((result) => {
                // Jika user klik "Return Book" dan form valid
                if (result.isConfirmed) {
                    submitReturnForm(borrowingId, result.value);
                }
            });
        }

        // menampilkan pop up untuk return option
        function createReturnForm() {
            return `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Return Reason <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                                <input type="radio" name="return_reason" value="normal" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Normal</span>
                                    <p class="text-xs text-gray-600">Book returned in good condition</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition">
                                <input type="radio" name="return_reason" value="user_missing" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">User Missing</span>
                                    <p class="text-xs text-gray-600">User cannot be contacted</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition">
                                <input type="radio" name="return_reason" value="book_damaged" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Book Damaged</span>
                                    <p class="text-xs text-gray-600">Book returned in damaged condition</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-500 hover:bg-red-50 transition">
                                <input type="radio" name="return_reason" value="book_lost" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Book Lost</span>
                                    <p class="text-xs text-gray-600">Book cannot be returned</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label for="return_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea 
                            id="return_notes" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Add notes if needed...">
                        </textarea>
                    </div>
                </div>
            `;
        }

        // Fungsi untuk validasi dan ambil data dari form
        function validateAndGetFormData() {
            const selectedReason = document.querySelector('input[name="return_reason"]:checked');
            const notes = document.getElementById('return_notes').value;
            
            if (!selectedReason) {
                Swal.showValidationMessage('Please select a return reason');
                return false;
            }
            
            // Return data yang akan disubmit
            return {
                return_reason: selectedReason.value,
                return_notes: notes
            };
        }

        // Fungsi untuk submit form return ke server
        function submitReturnForm(borrowingId, formData) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/borrowings/' + borrowingId + '/return';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';
            form.appendChild(methodField);
            
            const reasonField = document.createElement('input');
            reasonField.type = 'hidden';
            reasonField.name = 'return_reason';
            reasonField.value = formData.return_reason;
            form.appendChild(reasonField);
            
            if (formData.return_notes) {
                const notesField = document.createElement('input');
                notesField.type = 'hidden';
                notesField.name = 'return_notes';
                notesField.value = formData.return_notes;
                form.appendChild(notesField);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>

@endsection
