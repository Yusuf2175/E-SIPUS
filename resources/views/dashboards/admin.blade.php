<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <x-sidebar-admin />

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Admin Dashboard</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="text-red-700 font-semibold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 lg:p-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Welcome, {{ $user->name }}!</h1>
                    <p class="text-slate-600">Administrator Dashboard - Manage the entire library system</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_users'] }}</p>
                        <p class="text-sm text-slate-600">Total Users</p>
                        <p class="text-xs text-blue-600 mt-2">{{ $stats['total_regular_users'] }} regular users</p>
                    </div>

                    <!-- Total Books -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_books'] }}</p>
                        <p class="text-sm text-slate-600">Total Books</p>
                        <p class="text-xs text-green-600 mt-2">{{ $stats['available_books'] }} available</p>
                    </div>

                    <!-- Active Borrowings -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Pending Requests -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['pending_requests'] }}</p>
                        <p class="text-sm text-slate-600">Pending Requests</p>
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
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Administrator</p>
                                        <p class="text-xs text-slate-600">Full access</p>
                                    </div>
                                </div>
                                <p class="text-2xl font-bold text-red-600">{{ $stats['total_admins'] }}</p>
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

                            <a href="{{ route('admin.role.requests') }}" class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition group">
                                <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">Role Requests</p>
                                    <p class="text-xs text-slate-600">{{ $stats['pending_requests'] }} pending</p>
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

                    <!-- Pending Role Requests -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Pending Role Requests</h3>
                            <a href="{{ route('admin.role.requests') }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                                View All →
                            </a>
                        </div>
                        @if($pendingRoleRequests->isEmpty())
                            <p class="text-center text-slate-500 py-8">No pending requests</p>
                        @else
                            <div class="space-y-3">
                                @foreach($pendingRoleRequests as $request)
                                    <div class="p-4 border border-yellow-200 bg-yellow-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="font-semibold text-slate-800">{{ $request->user->name }}</p>
                                            <span class="text-xs px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full">Pending</span>
                                        </div>
                                        <p class="text-sm text-slate-600 mb-2">Request: <span class="font-medium">{{ ucfirst($request->requested_role) }}</span></p>
                                        <p class="text-xs text-slate-500">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
