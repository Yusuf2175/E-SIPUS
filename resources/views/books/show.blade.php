@extends('layouts.dashboard')

@section('page-title', 'Bibliographic Information')

@section('content')

        <div class="flex-1 -mt-10">
            <div class="p-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('books.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
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
                                        $isBorrowedByCurrentUser = $book->isBorrowedByUser(auth()->id());
                                        $actualAvailableCopies = $book->getActualAvailableCopies();
                                    @endphp
                                    
                                    @if($isBorrowedByCurrentUser)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-ungu/50 text-white">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Anda Sedang Meminjam
                                        </span>
                                    @elseif($actualAvailableCopies > 0)
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Tersedia ({{ $actualAvailableCopies }} salinan)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tidak Tersedia (Semua Dipinjam)
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
                                    $isBorrowedByCurrentUser = $book->isBorrowedByUser(auth()->id());
                                    $actualAvailableCopies = $book->getActualAvailableCopies();
                                    
                                    // Check for unpaid penalties
                                    $unpaidPenalties = \App\Models\Borrowing::where('user_id', auth()->id())
                                        ->where('penalty_amount', '>', 0)
                                        ->where('penalty_paid', false)
                                        ->get();
                                    $hasUnpaidPenalty = $unpaidPenalties->count() > 0;
                                    $totalUnpaidPenalty = $unpaidPenalties->sum('penalty_amount');
                                @endphp
                                
                                <!-- Unpaid Penalty Warning Banner -->
                                @if($hasUnpaidPenalty)
                                    <div class="w-full bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-300 rounded-xl p-5 mb-2">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold text-red-800 mb-1">⚠️ Unpaid Penalty Alert</h4>
                                                <p class="text-sm text-red-700 mb-2">
                                                    You have <strong>{{ $unpaidPenalties->count() }} unpaid {{ $unpaidPenalties->count() > 1 ? 'penalties' : 'penalty' }}</strong> totaling 
                                                    <strong class="text-lg">Rp {{ number_format($totalUnpaidPenalty, 0, ',', '.') }}</strong>
                                                </p>
                                                <p class="text-sm text-red-600 font-semibold">
                                                    🔒 You cannot borrow new books until all penalties are paid. Please contact admin or staff for payment.
                                                </p>
                                                <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="inline-flex items-center mt-3 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Penalty Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($isBorrowedByCurrentUser)
                                    <button disabled class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-700 font-semibold rounded-lg cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Anda Sudah Meminjam Buku Ini
                                    </button>
                                @elseif($hasUnpaidPenalty)
                                    <button disabled class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-600 font-semibold rounded-lg cursor-not-allowed opacity-60" title="Cannot borrow with unpaid penalty">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Pinjam Buku (Terkunci)
                                    </button>
                                @elseif($actualAvailableCopies > 0)
                                    <form action="{{ route('borrowings.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition-all">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="inline-flex items-center px-6 py-3 bg-slate-300 text-slate-600 font-semibold rounded-lg cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Tidak Tersedia (Semua Dipinjam)
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
                                            Hapus dari Koleksi
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
                                            Tambah ke Koleksi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">Ulasan & Rating</h2>

                    <!-- Add Review Form -->
                    @if(!$userReview)
                        <div class="mb-8 p-6 bg-slate-50 rounded-xl">
                            <h3 class="text-lg font-semibold text-gray-400 mb-4">Tulis Ulasan Anda</h3>
                            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="rating" id="ratingInput" value="">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Rating</label>
                                    <div class="flex gap-2" id="starRating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" class="star-btn focus:outline-none transition-transform hover:scale-110" data-rating="{{ $i }}">
                                                <svg class="w-10 h-10 text-slate-300 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-400 mt-2" id="ratingText">Pilih rating Anda</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Ulasan</label>
                                    <textarea name="review" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-400" placeholder="Bagikan pengalaman Anda tentang buku ini..." required></textarea>
                                </div>

                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition-all">
                                    Kirim Ulasan
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
                                        ratingText.classList.remove('text-gray-400', 'text-red-600', 'text-orange-600', 'text-yellow-600', 'text-lime-600', 'text-green-600');
                                        
                                        // Add new color class
                                        ratingText.classList.add(ratingData.color, 'font-semibold');
                                    } else if (!isHover) {
                                        ratingText.textContent = 'Pilih rating Anda';
                                        ratingText.classList.remove('text-red-600', 'text-orange-600', 'text-yellow-600', 'text-lime-600', 'text-green-600', 'font-semibold');
                                        ratingText.classList.add('text-gray-400');
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
                                <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" onsubmit="return confirm('Hapus ulasan Anda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Hapus</button>
                                </form>
                            </div>
                            <p class="text-slate-700">{{ $userReview->review }}</p>
                            <p class="text-sm text-slate-500 mt-2">{{ $userReview->created_at->diffForHumans() }}</p>
                        </div>
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
    
@endsection
