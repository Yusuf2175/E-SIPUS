@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class="mb-16">
        <div class="flex flex-col items-center">
                <h1 class="text-3xl font-bold text-slate-800">Welcome back, {{ $user->name }}!</h1>
                <p class="text-slate-600 mt-1">Manage your library activities with ease</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Currently Borrowed -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-ungu rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="bg-cstm px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-primarys">Active</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['active_borrowings'] }}</p>
            <p class="text-sm font-medium text-slate-600">Currently Borrowed</p>
        </div>

        <!-- Total Borrowings -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="bg-blue-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-blue-600">Total</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_borrowings'] }}</p>
            <p class="text-sm font-medium text-slate-600">Total Borrowings</p>
        </div>

        <!-- My Collection -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-pink-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="bg-pink-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-pink-600">Saved</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['collections'] }}</p>
            <p class="text-sm font-medium text-slate-600">My Collection</p>
        </div>

        <!-- Reviews Given -->
        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 bg-amber-500 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="bg-amber-50 px-3 py-1 rounded-full">
                    <span class="text-xs font-semibold text-amber-600">Reviews</span>
                </div>
            </div>
            <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['reviews'] }}</p>
            <p class="text-sm font-medium text-slate-600">Reviews Given</p>
        </div>
    </div>

    <!-- CTA Return Books -->
    @if($stats['active_borrowings'] > 0)
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
                        <p class="text-white/90 text-sm">You have <span class="font-bold">{{ $stats['active_borrowings'] }} book{{ $stats['active_borrowings'] > 1 ? 's' : '' }}</span> currently borrowed. Don't forget to return them on time!</p>
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
        <div class="lg:col-span-2">
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
                        <p class="text-slate-500 font-medium mb-4">No borrowings yet</p>
                        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-ungu hover:bg-primarys text-white font-semibold rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Browse Books
                        </a>
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
                                    <p class="text-sm text-slate-600 truncate">{{ $borrowing->book->author }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $borrowing->status === 'borrowed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $borrowing->status === 'borrowed' ? 'Borrowed' : 'Returned' }}
                                        </span>
                                        <span class="text-xs text-slate-500">{{ $borrowing->borrowed_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('books.show', $borrowing->book) }}" class="text-ungu hover:text-primarys transition flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">Browse Books</p>
                            <p class="text-xs text-slate-600">Find your favorite books</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-primarys transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('collections.index') }}" class="flex items-center gap-3 p-4 bg-pink-50 border border-pink-200 rounded-xl hover:bg-pink-100 transition group">
                        <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">My Collection</p>
                            <p class="text-xs text-slate-600">View favorite books</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-pink-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <p class="font-semibold text-slate-800">My Borrowings</p>
                            <p class="text-xs text-slate-600">Manage borrowings</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Books -->
    @if($recommendedBooks->isNotEmpty())
        <div class="mt-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cstm rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Recommended Books</h2>
                </div>
                <a href="{{ route('books.index') }}" class="text-ungu hover:text-primarys font-semibold flex items-center gap-1 transition">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($recommendedBooks as $book)
                    <a href="{{ route('books.show', $book) }}" class="group">
                        <div class="bg-white rounded-xl border-2 border-slate-100 overflow-hidden hover:border-ungu transition-all">
                            <div class="aspect-[3/4] bg-gradient-to-br from-ungu/10 to-primarys/10 relative overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-ungu/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-slate-800 text-sm line-clamp-2 mb-1">{{ $book->title }}</h3>
                                <p class="text-xs text-slate-600 line-clamp-1">{{ $book->author }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Role Request Modal -->
    <div id="roleRequestModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Request Role Change</h2>
                <button onclick="document.getElementById('roleRequestModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('user.request.role') }}" method="POST">
                @csrf
                <input type="hidden" name="requested_role" value="petugas">
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason for Request</label>
                    <textarea name="reason" rows="4" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-ungu focus:border-ungu transition" placeholder="Explain why you want to become a staff member..." required></textarea>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-ungu hover:bg-primarys text-white font-semibold rounded-xl transition">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
@endsection
