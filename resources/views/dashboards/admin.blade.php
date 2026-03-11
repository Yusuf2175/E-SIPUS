@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex items-start justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-base">{{ now()->format('l, F d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-bold text-slate-900 mb-3">Welcome back, {{ $user->name }} 👋</h1>
                <p class="text-lg text-slate-500">Here's an overview of your library today.</p>
            </div>
            
            <!-- CTA Report -->
            <div class="flex-shrink-0">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 bg-ungu hover:bg-primarys text-white px-5 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Full Report
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Books -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:-translate-y-1 transition-transform duration-300 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-slate-400 mb-2 font-medium">Total Books</p>
                    <h3 class="text-4xl font-bold text-slate-900">{{ number_format($stats['total_books']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-purple-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:-rotate-6 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:-translate-y-1 transition-transform duration-300 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-slate-400 mb-2 font-medium">Total Users</p>
                    <h3 class="text-4xl font-bold text-slate-900">{{ number_format($stats['total_users']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:-rotate-6 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Borrowings -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:-translate-y-1 transition-transform duration-300 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-slate-400 mb-2 font-medium">Borrowings</p>
                    <h3 class="text-4xl font-bold text-slate-900">{{ number_format($stats['my_active_borrowings']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:-rotate-6 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:-translate-y-1 transition-transform duration-300 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-slate-400 mb-2 font-medium">Categories</p>
                    <h3 class="text-4xl font-bold text-slate-900">{{ number_format($stats['total_categories']) }}</h3>
                </div>
                <div class="w-14 h-14 bg-teal-500 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:-rotate-6 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
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
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Recent Borrowings</h2>
                    </div>
                    <a href="{{ route('borrowings.index') }}" class="text-sm text-ungu hover:text-primarys font-medium transition flex items-center gap-1">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                @if($recentBorrowings->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">No borrowings yet</p>
                    </div>
                @else
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 px-4 py-3 bg-slate-50 rounded-lg mb-3">
                        <div class="col-span-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">User</div>
                        <div class="col-span-1 text-xs font-semibold text-slate-500 uppercase tracking-wide">Action</div>
                        <div class="col-span-4 text-xs font-semibold text-slate-500 uppercase tracking-wide">Book</div>
                        <div class="col-span-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Date</div>
                        <div class="col-span-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</div>
                    </div>

                    <!-- Table Body -->
                    <div class="space-y-2">
                        @foreach($recentBorrowings->take(5) as $borrowing)
                            <div class="grid grid-cols-12 gap-4 px-4 py-4 hover:bg-slate-50 rounded-lg transition items-center">
                                <!-- User -->
                                <div class="col-span-3 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-ungu to-primarys rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($borrowing->user->name, 0, 2)) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-slate-900 truncate">{{ $borrowing->user->name }}</span>
                                </div>

                                <!-- Action -->
                                <div class="col-span-1">
                                    @if($borrowing->status === 'borrowed')
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Book -->
                                <div class="col-span-4">
                                    <span class="text-sm text-slate-900 truncate block">{{ $borrowing->book->title }}</span>
                                </div>

                                <!-- Date -->
                                <div class="col-span-2">
                                    <span class="text-sm text-slate-500">{{ $borrowing->borrowed_date->format('M d, Y') }}</span>
                                </div>

                                <!-- Status -->
                                <div class="col-span-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium {{ $borrowing->status === 'borrowed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $borrowing->status === 'borrowed' ? 'Active' : 'Completed' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Staff Statistics & Quick Actions -->
        <div class="space-y-6">
            <!-- Staff Statistics -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Staff Overview</h3>
                </div>
                
                <div class="space-y-3">
                    <!-- Administrator Card -->
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-ungu to-primarys p-4">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-white">
                                        <p class="font-semibold">Administrator</p>
                                        <p class="text-xs text-white/80">Full system access</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-white">{{ $stats['total_admins'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    </div>

                    <!-- Petugas Card -->
                    <div class="rounded-xl bg-green-50 border border-green-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Petugas</p>
                                    <p class="text-xs text-slate-600">Library staff members</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-green-600">{{ $stats['total_petugas'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Quick Actions</h3>
                    <p class="text-xs text-slate-500">Frequently used actions</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <!-- Manage Users -->
                    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-2 p-4 bg-slate-50 rounded-xl hover:bg-blue-50 hover:border-blue-200 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-slate-900 text-sm mb-0.5">Manage Users</p>
                            <p class="text-xs text-slate-500">View and manage</p>
                        </div>
                    </a>

                    <!-- Manage Books -->
                    <a href="{{ route('books.index') }}" class="flex flex-col items-center gap-2 p-4 bg-slate-50 rounded-xl hover:bg-purple-50 hover:border-purple-200 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-ungu rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md shadow-ungu/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-slate-900 text-sm mb-0.5">Add Book</p>
                            <p class="text-xs text-slate-500">Add new book</p>
                        </div>
                    </a>

                    <!-- Manage Staff -->
                    <a href="{{ route('admin.petugas.index') }}" class="flex flex-col items-center gap-2 p-4 bg-slate-50 rounded-xl hover:bg-green-50 hover:border-green-200 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md shadow-green-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-slate-900 text-sm mb-0.5">Manage Staff</p>
                            <p class="text-xs text-slate-500">Manage petugas</p>
                        </div>
                    </a>

                    <!-- Manage Books -->
                    <a href="{{ route('books.index') }}" class="flex flex-col items-center gap-2 p-4 bg-slate-50 rounded-xl hover:bg-orange-50 hover:border-orange-200 border border-transparent transition-all group">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md shadow-orange-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-slate-900 text-sm mb-0.5">Manage Books</p>
                            <p class="text-xs text-slate-500">View all books</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
