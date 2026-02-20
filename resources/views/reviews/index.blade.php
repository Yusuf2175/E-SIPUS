@extends('layouts.dashboard')

@section('page-title', 'Reviews & Ratings')

@section('content')
        <div class="flex-1 -mt-10">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">User Feedback Management</h1>
                    <p class="text-slate-600">Monitor and manage book reviews from library members</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Statistics Cards (Admin & Petugas Only) -->
                @if($stats)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Reviews -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_reviews'] }}</p>
                            <p class="text-sm text-slate-600">Total Reviews</p>
                        </div>

                        <!-- Average Rating -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-500">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['average_rating'] }}</p>
                            <p class="text-sm text-slate-600">Average Rating</p>
                        </div>

                        <!-- 5 Star Reviews -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <span class="text-green-600 font-bold text-lg">5★</span>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['five_star'] }}</p>
                            <p class="text-sm text-slate-600">5-Star Reviews</p>
                        </div>

                        <!-- Low Rating Reviews -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-red-500">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                    <span class="text-red-600 font-bold text-lg">≤2★</span>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['one_star'] + $stats['two_star'] }}</p>
                            <p class="text-sm text-slate-600">Low Ratings</p>
                        </div>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Rating Distribution</h3>
                        <div class="space-y-3">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = $stats[['one_star', 'two_star', 'three_star', 'four_star', 'five_star'][$star - 1]];
                                    $percentage = $stats['total_reviews'] > 0 ? ($count / $stats['total_reviews']) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1 w-20">
                                        <span class="text-sm font-medium text-slate-700">{{ $star }}</span>
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="w-full bg-slate-200 rounded-full h-3">
                                            <div class="bg-yellow-400 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 w-16 text-right">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <form method="GET" action="{{ route('reviews.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews, books, or users..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                            <select name="rating" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Ratings</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Book Filter (Admin & Petugas Only) -->
                        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Book</label>
                                <select name="book_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">All Books</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                Filter
                            </button>
                            <a href="{{ route('reviews.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Reviews List -->
                @if($reviews->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 mb-2">No Reviews Yet</h3>
                        <p class="text-slate-600">
                            @if(Auth::user()->isUser())
                                You haven't given any reviews for books yet
                            @else
                                No reviews available yet
                            @endif
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <!-- Book Cover -->
                                    <div class="shrink-0">
                                        @if($review->book->cover_image)
                                            <img src="{{ asset('storage/' . $review->book->cover_image) }}" alt="{{ $review->book->title }}" class="w-20 h-28 object-cover rounded-lg">
                                        @else
                                            <div class="w-20 h-28 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Review Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Header -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $review->book->title }}</h3>
                                                <p class="text-sm text-slate-600">{{ $review->book->author }}</p>
                                            </div>
                                            
                                            <!-- Rating Stars -->
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>

                                        <!-- Review Text -->
                                        <p class="text-slate-700 mb-4">{{ $review->review }}</p>

                                        <!-- Footer -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3 text-sm text-slate-600">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                        <span class="text-purple-700 font-semibold text-xs">{{ substr($review->user->name, 0, 2) }}</span>
                                                    </div>
                                                    <span class="font-medium">{{ $review->user->name }}</span>
                                                </div>
                                                <span>•</span>
                                                <span>{{ $review->created_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- Actions -->
                                            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                                                <form action="{{ route('reviews.admin.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
