@extends('layouts.dashboard')

@section('page-title', $book->title)

@section('content')

    {{-- Back --}}
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Books
        </a>
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

                <!-- Book Detail Card -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8">
                    <div class="md:flex">
                        <!-- Book Cover -->
                        <div class="md:w-1/3 lg:w-1/4">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-96 md:h-full object-cover">
                            @else
                                <div class="w-full h-96 md:h-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                    <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Book Information -->
                        <div class="md:w-2/3 lg:w-3/4 p-8">
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex-1">
                                    <h1 class="text-4xl font-bold text-slate-800 mb-3">{{ $book->title }}</h1>
                                    <p class="text-xl text-slate-600 mb-4">oleh {{ $book->author }}</p>
                                    
                                    <!-- Rating -->
                                    @if($totalReviews > 0)
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-slate-600 font-medium">{{ number_format($averageRating, 1) }}</span>
                                            <span class="text-slate-500">({{ $totalReviews }} ulasan)</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Availability Badge -->
                                <div>
                                    @php
                                        // Check if user has any borrowing for this book (pending, approved, or borrowed)
                                        $userBorrowing = \App\Models\Borrowing::where('user_id', auth()->id())
                                            ->where('book_id', $book->id)
                                            ->whereIn('status', ['pending', 'approved', 'borrowed'])
                                            ->first();
                                        
                                        $actualAvailableCopies = $book->getActualAvailableCopies();
                                    @endphp
                                    
                                    @if($userBorrowing)
                                        @if($userBorrowing->status === 'pending')
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                                <svg class="w-4 h-4 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Waiting for Approval
                                            </span>
                                        @elseif($userBorrowing->status === 'approved' || $userBorrowing->status === 'borrowed')
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-ungu/50 text-white">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                You Are Borrowing
                                            </span>
                                        @endif
                                    @elseif($actualAvailableCopies > 0)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Available ({{ $actualAvailableCopies }} {{ $actualAvailableCopies > 1 ? 'copies' : 'copy' }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Not Available (All Borrowed)
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Book Details Grid -->
                            <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b border-slate-200">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">ISBN</p>
                                    <p class="font-semibold text-slate-800">{{ $book->isbn }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Kategori</p>
                                    <p class="font-semibold text-slate-800">{{ $book->category }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Penerbit</p>
                                    <p class="font-semibold text-slate-800">{{ $book->publisher ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Tahun Terbit</p>
                                    <p class="font-semibold text-slate-800">{{ $book->published_year ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Stok Tersedia</p>
                                    <p class="font-semibold text-slate-800">{{ $book->getActualAvailableCopies() }} / {{ $book->total_copies }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Ditambahkan</p>
                                    <p class="font-semibold text-slate-800">{{ $book->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($book->description)
                                <div class="mb-6">
                                    <h3 class="text-lg font-bold text-slate-800 mb-3">Deskripsi</h3>
                                    <p class="text-slate-700 leading-relaxed">{{ $book->description }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                @php
                                    // Check if user has any borrowing for this book (pending, approved, or borrowed)
                                    $currentBorrowing = \App\Models\Borrowing::where('user_id', auth()->id())
                                        ->where('book_id', $book->id)
                                        ->whereIn('status', ['pending', 'approved', 'borrowed'])
                                        ->first();
                                    
                                    $actualAvailableCopies = $book->getActualAvailableCopies();
                                @endphp
                                
                                @if($currentBorrowing)
                                    @if($currentBorrowing->status === 'pending')
                                        <!-- Pending Status - Button Only -->
                                        <button disabled class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-700 font-semibold rounded-lg cursor-not-allowed">
                                            <svg class="w-5 h-5 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Waiting for Approval
                                        </button>
                                    @elseif($currentBorrowing->status === 'approved' || $currentBorrowing->status === 'borrowed')
                                        <!-- Approved/Borrowed Status -->
                                        <div class="w-full p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                                            <div class="flex items-start gap-3">
                                                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-blue-800 mb-1">You Are Currently Borrowing This Book</h4>
                                                    <p class="text-sm text-blue-700 mb-2">Please return the book before the due date to avoid late fees.</p>
                                                    <div class="text-xs text-blue-600">
                                                        <p>Borrowed on: {{ $currentBorrowing->borrowed_date->format('d M Y') }}</p>
                                                        <p>Due date: {{ $currentBorrowing->due_date->format('d M Y') }}</p>
                                                        @if($currentBorrowing->approved_by)
                                                            <p>Approved by: {{ $currentBorrowing->approvedBy->name }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button disabled class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-700 font-semibold rounded-lg cursor-not-allowed">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            You Already Borrowed This Book
                                        </button>
                                    @endif
                                @elseif($actualAvailableCopies > 0)
                                    <!-- Available to Borrow -->
                                    <form action="{{ route('borrowings.store') }}" method="POST" id="borrowForm">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="button" onclick="showBorrowModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Request to Borrow
                                        </button>
                                    </form>
                                @else
                                    <!-- Not Available -->
                                    <button disabled class="inline-flex items-center px-6 py-3 bg-slate-300 text-slate-600 font-semibold rounded-lg cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Not Available (All Borrowed)
                                    </button>
                                @endif

                                @if($inCollection)
                                    <form action="{{ route('collections.destroy', $book->collections()->where('user_id', auth()->id())->first()) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                           Remove from Collection
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('collections.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            Add to Collection
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">Reviews and Ratings</h2>

                    <!-- Add Review Form -->
                    @if(!$userReview && $hasApprovedBorrowing)
                        <div class="mb-8 p-6 bg-slate-50 rounded-xl">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Write Your Review</h3>
                            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="rating" id="ratingInput" value="">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Ratings</label>
                                    <div class="flex gap-2" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" class="star-btn focus:outline-none transition-transform hover:scale-110" data-rating="{{ $i }}">
                                                <svg class="w-10 h-10 text-slate-300 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-slate-600 mt-2" id="ratingText">Pilih rating Anda</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Review</label>
                                    <textarea name="review" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-slate-700" placeholder="Bagikan pengalaman Anda tentang buku ini..." required></textarea>
                                </div>

                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition-all">
                                    Send Review
                                </button>
                            </form>
                        </div>

                        {{-- js untuk mengatur rating bintang option --}}
                         <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const starButtons = document.querySelectorAll('.star-btn');
                                const ratingInput = document.getElementById('ratingInput');
                                const ratingText = document.getElementById('ratingText');
                                const reviewForm = document.getElementById('reviewForm');
                                let selectedRating = 0;

                                // Rating text labels with colors
                                const ratingLabels = {
                                    1: { text: 'Sangat Buruk', color: 'text-red-600' },
                                    2: { text: 'Buruk', color: 'text-orange-600' },
                                    3: { text: 'Cukup', color: 'text-yellow-600' },
                                    4: { text: 'Bagus', color: 'text-lime-600' },
                                    5: { text: 'Sangat Bagus', color: 'text-green-600' }
                                };

                                // Function to update stars display
                                function updateStars(rating, isHover = false) {
                                    starButtons.forEach((btn, index) => {
                                        const star = btn.querySelector('svg');
                                        if (index < rating) {
                                            star.classList.remove('text-slate-300');
                                            star.classList.add('text-yellow-400');
                                        } else {
                                            star.classList.remove('text-yellow-400');
                                            star.classList.add('text-slate-300');
                                        }
                                    });

                                    if (rating > 0) {
                                        const ratingData = ratingLabels[rating];
                                        ratingText.textContent = ratingData.text;
                                        
                                        // Remove all color classes
                                        ratingText.classList.remove('text-slate-600', 'text-red-600', 'text-orange-600', 'text-yellow-600', 'text-lime-600', 'text-green-600');
                                        
                                        // Add new color class
                                        ratingText.classList.add(ratingData.color, 'font-semibold');
                                    } else if (!isHover) {
                                        ratingText.textContent = 'Pilih rating Anda';
                                        ratingText.classList.remove('text-red-600', 'text-orange-600', 'text-yellow-600', 'text-lime-600', 'text-green-600', 'font-semibold');
                                        ratingText.classList.add('text-slate-600');
                                    }
                                }

                                // Click event
                                starButtons.forEach((btn, index) => {
                                    btn.addEventListener('click', function() {
                                        selectedRating = index + 1;
                                        ratingInput.value = selectedRating;
                                        updateStars(selectedRating);
                                    });

                                    // Hover event
                                    btn.addEventListener('mouseenter', function() {
                                        updateStars(index + 1, true);
                                    });
                                });

                                // Mouse leave - return to selected rating
                                document.getElementById('starRating').addEventListener('mouseleave', function() {
                                    updateStars(selectedRating);
                                });

                                // Form validation
                                reviewForm.addEventListener('submit', function(e) {
                                    if (!ratingInput.value) {
                                        e.preventDefault();
                                        alert('Silakan pilih rating terlebih dahulu!');
                                        return false;
                                    }
                                });
                            });
                        </script>
                    @elseif(!$userReview && !$hasApprovedBorrowing)
                        <!-- Message for users who haven't borrowed the book -->
                        <div class="mb-8 p-6 bg-amber-50 border-2 border-amber-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-amber-800 mb-1">Belum Bisa Memberikan Review</h4>
                                    <p class="text-sm text-amber-700">Anda harus meminjam dan mendapatkan persetujuan untuk buku ini terlebih dahulu sebelum dapat memberikan review dan rating.</p>
                                </div>
                            </div>
                        </div>
                       
                    @else
                        <!-- User's Review -->
                        <div class="mb-8 p-6 bg-purple-50 border-2 border-purple-200 rounded-xl">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-slate-800">Ulasan Anda</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                {{-- Tombol Edit & Hapus --}}
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="openEditReviewModal({{ $userReview->id }}, {{ $userReview->rating }}, {{ json_encode($userReview->review) }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form action="{{ route('reviews.destroy', $userReview) }}" method="POST"
                                          onsubmit="return confirm('Hapus ulasan Anda?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-slate-700">{{ $userReview->review }}</p>
                            <p class="text-sm text-slate-500 mt-2">{{ $userReview->created_at->diffForHumans() }}
                                @if($userReview->updated_at->gt($userReview->created_at))
                                    <span class="text-xs text-purple-500 ml-1">(diedit)</span>
                                @endif
                            </p>
                        </div>

                        {{-- Modal Edit Review --}}
                        <div id="editReviewModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6">
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-lg font-bold text-slate-800">Edit Ulasan</h3>
                                    <button onclick="closeEditReviewModal()" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <form id="editReviewForm" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="rating" id="editRatingInput">

                                    {{-- Star Rating --}}
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                                        <div class="flex gap-2" id="editStarRating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" class="edit-star-btn focus:outline-none transition-transform hover:scale-110" data-rating="{{ $i }}">
                                                    <svg class="w-9 h-9 text-slate-300 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <p class="text-sm mt-1" id="editRatingText"></p>
                                    </div>

                                    {{-- Review Text --}}
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Ulasan</label>
                                        <textarea id="editReviewText" name="review" rows="4"
                                                  class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-slate-700 resize-none"
                                                  placeholder="Tulis ulasan Anda..." required></textarea>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" onclick="closeEditReviewModal()"
                                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                                class="flex-1 px-4 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-xl transition">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script>
                        (function () {
                            const ratingLabels = {
                                1: { text: 'Sangat Buruk', color: 'text-red-600' },
                                2: { text: 'Buruk',        color: 'text-orange-600' },
                                3: { text: 'Cukup',        color: 'text-yellow-600' },
                                4: { text: 'Bagus',        color: 'text-lime-600' },
                                5: { text: 'Sangat Bagus', color: 'text-green-600' },
                            };
                            let editSelectedRating = 0;

                            function updateEditStars(rating) {
                                document.querySelectorAll('.edit-star-btn svg').forEach((svg, i) => {
                                    svg.classList.toggle('text-yellow-400', i < rating);
                                    svg.classList.toggle('text-slate-300',  i >= rating);
                                });
                                const txt = document.getElementById('editRatingText');
                                txt.textContent  = rating ? ratingLabels[rating].text : '';
                                txt.className    = 'text-sm mt-1 font-semibold ' + (rating ? ratingLabels[rating].color : '');
                            }

                            document.querySelectorAll('.edit-star-btn').forEach((btn, i) => {
                                btn.addEventListener('click', () => {
                                    editSelectedRating = i + 1;
                                    document.getElementById('editRatingInput').value = editSelectedRating;
                                    updateEditStars(editSelectedRating);
                                });
                                btn.addEventListener('mouseenter', () => updateEditStars(i + 1));
                            });
                            document.getElementById('editStarRating').addEventListener('mouseleave', () => updateEditStars(editSelectedRating));

                            window.openEditReviewModal = function (id, rating, reviewText) {
                                editSelectedRating = rating;
                                document.getElementById('editRatingInput').value = rating;
                                document.getElementById('editReviewText').value  = reviewText;
                                document.getElementById('editReviewForm').action = `/reviews/${id}`;
                                updateEditStars(rating);
                                document.getElementById('editReviewModal').classList.remove('hidden');
                            };

                            window.closeEditReviewModal = function () {
                                document.getElementById('editReviewModal').classList.add('hidden');
                            };
                        })();
                        </script>
                    @endif

                    <!-- All Reviews -->
                    @if($book->reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($book->reviews as $review)
                                @if($userReview && $review->id === $userReview->id)
                                    @continue
                                @endif
                                <div class="p-6 border border-slate-200 rounded-xl">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $review->user->name }}</p>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-sm text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-slate-700">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <p class="text-slate-500">Belum ada ulasan untuk buku ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Confirmation Modal -->
    <div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" onclick="closeBorrowModal(event)">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto transform transition-all max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            <!-- Header - Fixed -->
            <div class="relative p-6 border-b border-slate-100 flex-shrink-0">
                <h3 class="text-2xl font-bold text-slate-800 text-center">Confirm Borrowing Request</h3>
                <button onclick="closeBorrowModal()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content - Scrollable -->
            <div class="p-6 space-y-6 overflow-y-auto flex-1">
                <!-- Important Information Alert -->
                <div class="flex items-start gap-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-blue-900 mb-1">Important Information</h4>
                        <p class="text-sm text-blue-800 leading-relaxed">Your borrowing request will be sent to admin/staff for approval. You will be notified once your request is processed.</p>
                    </div>
                </div>

                <!-- Book Details -->
                <div class="bg-slate-50 rounded-xl p-5 space-y-4">
                    <h4 class="font-semibold text-slate-800 text-lg mb-3">Borrowing Details</h4>
                    
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-start justify-between py-3 border-b border-slate-200">
                            <span class="text-sm font-medium text-slate-500">Book Title</span>
                            <span class="font-semibold text-slate-800 text-right max-w-xs">{{ $book->title }}</span>
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-slate-200">
                            <span class="text-sm font-medium text-slate-500">Author</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $book->author }}</span>
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-slate-200">
                            <span class="text-sm font-medium text-slate-500">Borrow Date</span>
                            <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-start justify-between py-3 border-b border-slate-200">
                            <span class="text-sm font-medium text-slate-500">Due Date</span>
                            <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::now()->addDays(14)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-start justify-between py-3">
                            <span class="text-sm font-medium text-slate-500">Borrow Period</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                14 days
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Notes <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <textarea 
                        name="notes" 
                        form="borrowForm" 
                        rows="3" 
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none text-slate-600 placeholder-slate-400" 
                        placeholder="Add any notes for your borrowing request..."></textarea>
                    <p class="text-xs text-slate-400 mt-1.5">You can add special requests or information here</p>
                </div>
            </div>

            <!-- Footer - Fixed -->
            <div class="p-6 border-t border-slate-100 flex-shrink-0 bg-slate-50">
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeBorrowModal()" 
                        class="flex-1 px-6 py-3.5 border-2 border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-white hover:border-slate-400 transition-all">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        form="borrowForm" 
                        class="flex-1 px-6 py-3.5 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Request
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBorrowModal() {
            const modal = document.getElementById('borrowModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeBorrowModal(event) {
            if (!event || event.target.id === 'borrowModal') {
                const modal = document.getElementById('borrowModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeBorrowModal();
            }
        });
    </script>

    <x-book-alert />
@endsection
