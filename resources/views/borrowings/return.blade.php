@extends('layouts.dashboard')

@section('page-title', 'Return Books')

@section('content')
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Return Books</h2>
                <p class="text-gray-600 mt-1">Return the books you have borrowed</p>
            </div>
            <a href="{{ route('borrowings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Borrowings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-blue-900 font-semibold mb-1">Return Information</h3>
                <ul class="text-blue-800 text-sm space-y-1">
                    <li>• Make sure the book is in good condition before returning</li>
                    <li>• Late returns will be subject to fines</li>
                    <li>• After returning, you can borrow other books</li>
                </ul>
            </div>
        </div>
    </div>

    @if($activeBorrowings->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Borrowed Books</h3>
            <p class="text-gray-600 mb-6">You haven't borrowed any books at the moment</p>
            <a href="{{ route('books.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Browse Books
            </a>
        </div>
    @else
        <!-- Active Borrowings List -->
        <div class="space-y-4">
            @foreach($activeBorrowings as $borrowing)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Book Cover -->
                            <div class="flex-shrink-0">
                                @if($borrowing->book->cover_image)
                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-24 h-32 object-cover rounded-lg">
                                @else
                                    <div class="w-24 h-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $borrowing->book->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $borrowing->book->author }}</p>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                            <div class="flex items-center text-sm">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-600">Borrowed: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}</span></span>
                                            </div>
                                            
                                            <div class="flex items-center text-sm">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-gray-600">Due Date: 
                                                    <span class="font-medium {{ $borrowing->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                                                        {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="mt-3">
                                            @if($borrowing->is_overdue)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Overdue {{ (int) abs($borrowing->days_remaining) }} day{{ abs($borrowing->days_remaining) > 1 ? 's' : '' }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ (int) $borrowing->days_remaining }} day{{ $borrowing->days_remaining > 1 ? 's' : '' }} left
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Return Button -->
                                    <div class="ml-4">
                                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" onsubmit="return confirm('Are you sure you want to return this book?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                                </svg>
                                                Return Book
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($borrowing->notes)
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $borrowing->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($borrowing->is_overdue)
                        <div class="bg-red-50 border-t border-red-200 px-6 py-3">
                            <div class="flex items-center text-sm text-red-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="font-medium">Warning:</span>
                                <span class="ml-1">This book has passed the return deadline. Please return it immediately to avoid further fines.</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Summary Card -->
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Borrowed Books</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $activeBorrowings->count() }}</p>
                    <p class="text-sm text-gray-600 mt-1">out of maximum 3 books</p>
                </div>
                <div class="text-right">
                    @if($activeBorrowings->where('is_overdue', true)->count() > 0)
                        <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg">
                            <p class="text-sm font-medium">Overdue Books</p>
                            <p class="text-2xl font-bold">{{ $activeBorrowings->where('is_overdue', true)->count() }}</p>
                        </div>
                    @else
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                            <p class="text-sm font-medium">All On Time</p>
                            <svg class="w-8 h-8 mx-auto mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection