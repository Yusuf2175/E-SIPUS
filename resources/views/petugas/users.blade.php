@extends('layouts.dashboard')

@section('page-title', 'Manage Users')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Manage Students</h1>
                <p class="text-slate-600 mt-1">View and manage all students in the library system</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-blue-500 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-ungu rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="bg-blue-50 px-3 py-1 rounded-full">
                        <span class="text-xs font-semibold text-ungu">Total</span>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-800 mb-1">{{ $users->total() }}</p>
                <p class="text-sm text-slate-600">Total Students</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border-2 border-slate-100 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-primarys to-secondrys px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Students Directory</h2>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-white">{{ $users->total() }} Records</span>
                </div>
            </div>
        </div>

        @if($users->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Students Found</h3>
                <p class="text-slate-600 max-w-md mx-auto">There are no students registered in the system yet. Students will appear here once they register.</p>
            </div>
        @else
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b-2 border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Student Info</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $index => $user)
                            <tr class="hover:bg-blue-50/50 transition group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center justify-center w-8 h-8 bg-slate-100 group-hover:bg-blue-100 rounded-lg transition">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-ungu">{{ $users->firstItem() + $index }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-primarys to-secondrys rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-base">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2 text-slate-700">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($user->address)
                                        <div class="flex items-start gap-2 max-w-xs">
                                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-slate-700 line-clamp-2 leading-relaxed">{{ $user->address }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-slate-400 italic">No address</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">{{ $user->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-xl text-xs font-bold">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        Active
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="px-6 py-5 border-t-2 border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="text-sm text-slate-600">
                            Showing <span class="font-bold text-slate-800">{{ $users->firstItem() }}</span> to <span class="font-bold text-slate-800">{{ $users->lastItem() }}</span> of <span class="font-bold text-slate-800">{{ $users->total() }}</span> students
                        </div>
                        <div class="flex items-center gap-2">
                            @if($users->onFirstPage())
                                <span class="px-4 py-2 text-slate-400 bg-slate-200 rounded-xl cursor-not-allowed flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition flex items-center gap-2 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Previous
                                </a>
                            @endif

                            <div class="flex items-center gap-1">
                                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                    @if($page == $users->currentPage())
                                        <span class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold shadow-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl transition font-semibold">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition flex items-center gap-2 font-semibold">
                                    Next
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="px-4 py-2 text-slate-400 bg-slate-200 rounded-xl cursor-not-allowed flex items-center gap-2">
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
@endsection
