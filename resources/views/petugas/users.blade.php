@extends('layouts.dashboard')

@section('page-title', 'Borrowing Users Management')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Borrowing Users Management</h1>
                <p class="text-slate-600 mt-1">Monitor and manage users with active book borrowings</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800 mb-1">{{ $users->total() }}</p>
                <p class="text-sm text-slate-600">Total Borrowing Users</p>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800 mb-1">{{ $users->where('active_borrowings_count', '>', 0)->count() }}</p>
                <p class="text-sm text-slate-600">Active Borrowers</p>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800 mb-1">{{ \App\Models\Borrowing::where('status', 'borrowed')->where('due_date', '<', now())->count() }}</p>
                <p class="text-sm text-slate-600">Overdue Books</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-ungu to-secondrys px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Borrowing Records</h2>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-white">{{ $users->total() }} Users</span>
                </div>
            </div>
        </div>

        @if($users->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Borrowing Records</h3>
                <p class="text-slate-600 max-w-md mx-auto">There are no users with borrowing records yet.</p>
            </div>
        @else
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b-2 border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Borrowed Books</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Penalty</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            @php
                                $activeBorrowings = $user->activeBorrowings;
                                $hasOverdue = $activeBorrowings->where('due_date', '<', now())->count() > 0;
                                $overdueCount = $activeBorrowings->where('due_date', '<', now())->count();
                                
                                // Calculate total penalty from active borrowings
                                $totalPenalty = 0;
                                foreach ($activeBorrowings as $borrowing) {
                                    $totalPenalty += $borrowing->calculatePenalty();
                                }
                                
                                // Also add unpaid penalties from returned borrowings
                                $unpaidPenalties = \App\Models\Borrowing::where('user_id', $user->id)
                                    ->where('status', 'returned')
                                    ->where('penalty_paid', false)
                                    ->where('penalty_amount', '>', 0)
                                    ->sum('penalty_amount');
                                
                                $totalPenalty += $unpaidPenalties;
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center justify-center w-10 h-10 bg-slate-100 rounded-lg">
                                        <span class="text-sm font-bold text-slate-700">#{{ $user->id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-ungu to-secondrys rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if($activeBorrowings->count() > 0)
                                        @if($hasOverdue)
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                                Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                On Time
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">
                                            No Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($activeBorrowings->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($activeBorrowings->take(2) as $borrowing)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                    <span class="text-slate-700 font-medium">{{ Str::limit($borrowing->book->title, 30) }}</span>
                                                </div>
                                            @endforeach
                                            @if($activeBorrowings->count() > 2)
                                                <p class="text-xs text-slate-500 font-medium">+{{ $activeBorrowings->count() - 2 }} more books</p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">No active borrowings</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if($totalPenalty > 0)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-lg font-bold text-red-600">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</span>
                                            @if($overdueCount > 0)
                                                <span class="text-xs text-slate-500">{{ $overdueCount }} overdue</span>
                                            @endif
                                            @if($unpaidPenalties > 0)
                                                <span class="text-xs text-amber-600 font-medium">+ unpaid penalties</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <button onclick="showUserDetail({{ $user->id }})" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-5 border-t-2 border-slate-200 bg-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Detail Modal (will be implemented with Alpine.js) -->
    <div x-data="{ showModal: false, userId: null }" x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal content will be loaded dynamically -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">User Borrowing Details</h3>
                    <button @click="showModal = false" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 rounded-lg flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modal-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showUserDetail(userId) {
            // For now, redirect to borrowings page filtered by user
            window.location.href = '{{ route("borrowings.index") }}?user_id=' + userId;
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection
