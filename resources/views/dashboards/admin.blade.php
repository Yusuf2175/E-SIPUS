@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="mb-8 -mt-5">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Welcome, {{ $user->name }}!</h1>
        <p class="text-slate-600">Administrator Dashboard - Manage the entire library system</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pt-5">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_users'] }}</p>
            <p class="text-sm font-semibold text-slate-600 mb-3">Total Users</p>
            <div class="pt-3 border-t border-slate-100">
                <p class="text-xs text-blue-600 font-medium">{{ $stats['total_regular_users'] }} regular users</p>
            </div>
        </div>

        <!-- Total Books -->
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_books'] }}</p>
            <p class="text-sm font-semibold text-slate-600 mb-3">Total Books</p>
            <div class="pt-3 border-t border-slate-100">
                <p class="text-xs text-green-600 font-medium">{{ $stats['available_books'] }} available</p>
            </div>
        </div>

        <!-- Active Borrowings -->
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['active_borrowings'] }}</p>
            <p class="text-sm font-semibold text-slate-600 mb-3">Currently Borrowed</p>
            <div class="pt-3 border-t border-slate-100">
                @if($stats['overdue_borrowings'] > 0)
                    <p class="text-xs text-red-600 font-medium">{{ $stats['overdue_borrowings'] }} overdue</p>
                @else
                    <p class="text-xs text-green-600 font-medium">No overdue books</p>
                @endif
            </div>
        </div>
    </div>

    <!-- CTA Return Books -->
    @if($myActiveBorrowings > 0)
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">You Have Borrowed Books</h3>
                        <p class="text-sm text-slate-600">You have <span class="font-semibold text-red-700">{{ $myActiveBorrowings }} book{{ $myActiveBorrowings > 1 ? 's' : '' }}</span> currently borrowed. Don't forget to return them on time!</p>
                    </div>
                </div>
                <a href="{{ route('borrowings.return.page') }}" class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shrink-0">
                    Return Books
                </a>
            </div>
        </div>
    @endif

    <!-- Staff Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Staff Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-ungu/10 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primarys rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Administrator</p>
                            <p class="text-xs text-slate-600">Full access</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-primarys">{{ $stats['total_admins'] }}</p>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Petugas</p>
                            <p class="text-xs text-slate-600">Library staff</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_petugas'] }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition group">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Manage Users</p>
                        <p class="text-xs text-slate-600">Manage all users</p>
                    </div>
                </a>
                <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition group">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Manage Books</p>
                        <p class="text-xs text-slate-600">Manage books</p>
                    </div>
                </a>

                <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition group">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Manage Borrowings</p>
                        <p class="text-xs text-slate-600">Manage borrowings</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Recent Users</h3>
                <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All →
                </a>
            </div>
            @if($recentUsers->isEmpty())
                <p class="text-center text-slate-500 py-8">No users yet</p>
            @else
                <div class="space-y-3">
                    @foreach($recentUsers as $recentUser)
                        <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg hover:border-blue-300 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-700 font-semibold text-sm">{{ substr($recentUser->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $recentUser->name }}</p>
                                    <p class="text-xs text-slate-600">{{ $recentUser->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $recentUser->role === 'admin' ? 'bg-red-100 text-red-700' : ($recentUser->role === 'petugas' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($recentUser->role) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
