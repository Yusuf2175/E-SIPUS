@extends('layouts.dashboard')

@section('page-title', 'Book Return Service')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Return Processing</h1>
                <p class="text-slate-600">Complete your book return transactions</p>
            </div>
            <a href="{{ route('borrowings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 text-slate-700 font-medium rounded-lg hover:border-primarys hover:text-primarys transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Borrowings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50  text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center gap-3 shadow-sm animate-fade-in">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-primarys rounded-xl p-6 mb-8 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-primarys rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-primarys font-bold text-lg mb-2">Return Information</h3>
                <ul class="text-slate-700 text-sm space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-primarys mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Make sure the book is in good condition before returning
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-primarys mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Late returns will be subject to fines
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-primarys mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        After returning, you can borrow other books
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if($activeBorrowings->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-primarys" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-3">No Borrowed Books</h3>
                <p class="text-slate-600 mb-8">You haven't borrowed any books at the moment. Start exploring our collection!</p>
                <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-primarys to-secondrys hover:from-secondrys hover:to-primarys text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Browse Books
                </a>
            </div>
        </div>
    @else
        <!-- Active Borrowings List -->
        <div class="space-y-6">
            @foreach($activeBorrowings as $borrowing)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 border border-slate-100">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Book Cover -->
                            <div class="flex-shrink-0">
                                @if($borrowing->book->cover_image)
                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-32 h-44 object-cover rounded-xl shadow-lg ring-2 ring-slate-100">
                                @else
                                    <div class="w-32 h-44 bg-gradient-to-br from-primarys to-secondrys rounded-xl flex items-center justify-center shadow-lg ring-2 ring-primarys/20">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $borrowing->book->title }}</h3>
                                        <p class="text-sm text-slate-600 mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $borrowing->book->author }}
                                        </p>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Borrowed Date</p>
                                                    <p class="text-sm font-semibold text-slate-800">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                                                <div class="w-10 h-10 {{ $borrowing->is_overdue ? 'bg-red-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 {{ $borrowing->is_overdue ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Due Date</p>
                                                    <p class="text-sm font-semibold {{ $borrowing->is_overdue ? 'text-red-600' : 'text-slate-800' }}">
                                                        {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="flex flex-wrap gap-2">
                                            @if($borrowing->is_overdue)
                                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Overdue {{ (int) abs($borrowing->days_remaining) }} day{{ abs($borrowing->days_remaining) > 1 ? 's' : '' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ (int) $borrowing->days_remaining }} day{{ $borrowing->days_remaining > 1 ? 's' : '' }} remaining
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Return Button -->
                                    <div class="flex-shrink-0">
                                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" id="return-form-{{ $borrowing->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" onclick="confirmReturn({{ $borrowing->id }}, '{{ $borrowing->book->title }}')" class="inline-flex items-center gap-2 bg-ungu hover:bg-primarys text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                                </svg>
                                                Return Book
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($borrowing->notes)
                                    <div class="mt-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-amber-800">Notes</p>
                                                <p class="text-sm text-amber-700 mt-1">{{ $borrowing->notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($borrowing->is_overdue)
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-t border-red-200 px-6 py-4">
                            <div class="flex items-center gap-3 text-sm text-red-800">
                                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold">Overdue Warning!</p>
                                    <p class="text-xs mt-0.5">This book has passed the return deadline. Please return it immediately to avoid further fines.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Summary Card -->
        <div class="mt-8 bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 rounded-2xl p-8 border-2 border-purple-100 shadow-lg">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Total Borrowed Books</h3>
                    <div class="flex items-baseline gap-2">
                        <p class="text-5xl font-bold text-primarys">{{ $activeBorrowings->count() }}</p>
                        <p class="text-slate-600">/ 3 books</p>
                    </div>
                    <div class="mt-3 w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primarys to-secondrys h-2 rounded-full transition-all duration-500" style="width: {{ ($activeBorrowings->count() / 3) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    @if($activeBorrowings->where('is_overdue', true)->count() > 0)
                        <div class="bg-white rounded-xl p-6 shadow-md border-2 border-red-200 text-center min-w-[140px]">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Overdue</p>
                            <p class="text-3xl font-bold text-red-600">{{ $activeBorrowings->where('is_overdue', true)->count() }}</p>
                        </div>
                    @else
                        <div class="bg-white rounded-xl p-6 shadow-md border-2 border-green-200 text-center min-w-[140px]">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Status</p>
                            <p class="text-lg font-bold text-green-600">All On Time</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmReturn(borrowingId, bookTitle) {
            Swal.fire({
                title: 'Return Book?',
                html: `Are you sure you want to return<br><strong>"${bookTitle}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#7C3AED',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Return It!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-semibold',
                    cancelButton: 'rounded-lg px-6 py-2.5 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Returning your book',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    document.getElementById('return-form-' + borrowingId).submit();
                }
            });
        }

        // Show success alert if session has success message
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#7C3AED',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-semibold'
                }
            });
        @endif

        // Show error alert if session has error message
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#7C3AED',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-semibold'
                }
            });
        @endif
    </script>
@endsection
