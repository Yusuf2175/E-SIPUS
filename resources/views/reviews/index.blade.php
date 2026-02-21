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

            <!-- Statistics Cards (Admin & Petugas Only) -->
            @if($stats)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Reviews -->
                    <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-blue-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['total_reviews'] }}</p>
                        <p class="text-sm font-medium text-slate-600">Total Reviews</p>
                        <p class="text-xs text-slate-400 mt-1">All user feedback</p>
                    </div>

                    <!-- Average Rating -->
                    <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-yellow-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2 mb-2">
                            <p class="text-4xl font-bold text-slate-800">{{ $stats['average_rating'] }}</p>
                            <p class="text-lg text-slate-400">/ 5.0</p>
                        </div>
                        <p class="text-sm font-medium text-slate-600">Average Rating</p>
                        <p class="text-xs text-slate-400 mt-1">Overall satisfaction</p>
                    </div>

                    <!-- 5 Star Reviews -->
                    <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-green-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                                <span class="text-green-600 font-bold text-2xl">5★</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['five_star'] }}</p>
                        <p class="text-sm font-medium text-slate-600">Excellent Reviews</p>
                        <p class="text-xs text-slate-400 mt-1">5-star ratings</p>
                    </div>

                    <!-- Low Rating Reviews -->
                    <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-red-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                                <span class="text-red-600 font-bold text-2xl">≤2★</span>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-slate-800 mb-2">{{ $stats['one_star'] + $stats['two_star'] }}</p>
                        <p class="text-sm font-medium text-slate-600">Needs Attention</p>
                        <p class="text-xs text-slate-400 mt-1">Low ratings (1-2 stars)</p>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-ungu/10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Filter Reviews</h3>
                            <p class="text-xs text-slate-500">Search and filter user feedback</p>
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['search', 'rating', 'book_id']))
                        <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-ungu transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear filters
                        </a>
                    @endif
                </div>
                
                <form method="GET" action="{{ route('reviews.index') }}">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by review, book title, or user name..." class="w-full pl-10 pr-4 py-3 border-2 border-slate-200 text-slate-700 rounded-xl focus:ring-2 focus:ring-ungu focus:border-ungu transition-all placeholder:text-slate-400">
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
                            <select name="rating" class="w-full px-4 py-3 border-2 border-slate-200 text-slate-700 rounded-xl focus:ring-2 focus:ring-ungu focus:border-ungu transition-all bg-white">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                            </select>
                        </div>

                        <!-- Book Filter (Admin & Petugas Only) -->
                        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                            <div class="lg:col-span-3">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Book</label>
                                <select name="book_id" class="w-full px-4 py-3 border-2 border-slate-200 text-slate-700 rounded-xl focus:ring-2 focus:ring-ungu focus:border-ungu transition-all bg-white">
                                    <option value="">All Books</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ Str::limit($book->title, 35) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-slate-100">
                        <button type="submit" class="flex-1 lg:flex-none lg:px-8  bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold py-3 rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        
                        <a href="{{ route('reviews.index') }}" class="flex-1 lg:flex-none lg:px-8 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Reviews List -->
            @if($reviews->isEmpty())
                <div class="bg-white rounded-2xl border-2 border-slate-100 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">No Reviews Found</h3>
                    <p class="text-slate-600">
                        @if(Auth::user()->isUser())
                            You haven't given any reviews for books yet
                        @else
                            No reviews available with the current filters
                        @endif
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-2xl border-2 border-slate-100 overflow-hidden hover:border-ungu/30 transition-all group">
                            <div class="flex flex-col md:flex-row">
                                <!-- Book Cover Section -->
                                <div class="md:w-48 bg-slate-50 p-6 flex items-center justify-center">
                                    @if($review->book->cover_image)
                                        <img src="{{ asset('storage/' . $review->book->cover_image) }}" alt="{{ $review->book->title }}" class="w-32 h-44 object-cover rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-32 h-44 bg-ungu rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Review Content Section -->
                                <div class="flex-1 p-6">
                                    <!-- Header with Book Info and Rating -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-ungu transition-colors">{{ $review->book->title }}</h3>
                                            <p class="text-sm text-slate-600 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $review->book->author }}
                                            </p>
                                        </div>
                                        
                                        <!-- Rating Badge -->
                                        <div class="flex items-center gap-2 bg-yellow-50 px-4 py-2 rounded-xl border border-yellow-200">
                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="font-bold text-slate-800">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>

                                    <!-- Rating Stars -->
                                    <div class="flex items-center gap-1 mb-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-6 h-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>

                                    <!-- Review Text -->
                                    <div class="bg-slate-50 rounded-xl p-4 mb-4">
                                        <p class="text-slate-700 leading-relaxed">{{ $review->review }}</p>
                                    </div>

                                    <!-- Footer with User Info and Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-ungu rounded-xl flex items-center justify-center shadow-sm">
                                                <span class="text-white font-bold text-sm">{{ substr($review->user->name, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">{{ $review->user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        <!-- Delete Action -->
                                        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                                            <form action="{{ route('reviews.admin.destroy', $review) }}" method="POST" id="delete-form-{{ $review->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $review->id }}, '{{ addslashes($review->book->title) }}')" class="inline-flex items-center gap-2 text-red-600 hover:text-white hover:bg-red-600 font-medium text-sm px-4 py-2 rounded-xl transition-all border-2 border-red-200 hover:border-red-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmDelete(reviewId, bookTitle) {
            Swal.fire({
                title: 'Delete Review?',
                html: `Are you sure you want to delete this review for<br><strong>"${bookTitle}"</strong>?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    document.getElementById('delete-form-' + reviewId).submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#7C3AED',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#7C3AED',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold'
                }
            });
        @endif
    </script>
@endsection
