<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <x-sidebar-user />

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Dashboard</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-700 font-semibold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 lg:p-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Welcome, {{ $user->name }}!</h1>
                    <p class="text-slate-600">Manage your library activities with ease</p>
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['active_borrowings'] }}</p>
                        <p class="text-sm text-slate-600">Currently Borrowed</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total_borrowings'] }}</p>
                        <p class="text-sm text-slate-600">Total Borrowings</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-pink-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['collections'] }}</p>
                        <p class="text-sm text-slate-600">My Collection</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['reviews'] }}</p>
                        <p class="text-sm text-slate-600">Reviews Given</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-slate-800">Recent Borrowings</h2>
                                <a href="{{ route('borrowings.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                    View All →
                                </a>
                            </div>

                            @if($recentBorrowings->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-slate-500 mb-4">No borrowings yet</p>
                                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                        Browse Books
                                    </a>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($recentBorrowings as $borrowing)
                                        <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl hover:border-purple-300 transition">
                                            <div class="w-16 h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                                @if($borrowing->book->cover_image)
                                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover rounded-lg">
                                                @else
                                                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-slate-800 truncate">{{ $borrowing->book->title }}</h3>
                                                <p class="text-sm text-slate-600">{{ $borrowing->book->author }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs px-2 py-1 rounded-full {{ $borrowing->status === 'borrowed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ $borrowing->status === 'borrowed' ? 'Borrowed' : 'Returned' }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">{{ $borrowing->borrowed_date->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('books.show', $borrowing->book) }}" class="text-purple-600 hover:text-purple-700">
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

                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Actions</h2>
                            <div class="space-y-3">
                                <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Browse Books</p>
                                        <p class="text-xs text-slate-600">Find your favorite books</p>
                                    </div>
                                </a>

                                <a href="{{ route('collections.index') }}" class="flex items-center gap-3 p-3 bg-pink-50 rounded-lg hover:bg-pink-100 transition group">
                                    <div class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">My Collection</p>
                                        <p class="text-xs text-slate-600">View favorite books</p>
                                    </div>
                                </a>

                                <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Borrowings</p>
                                        <p class="text-xs text-slate-600">Manage borrowings</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($recommendedBooks->isNotEmpty())
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-800">Recommended Books</h2>
                            <a href="{{ route('books.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                                View All →
                            </a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            @foreach($recommendedBooks as $book)
                                <a href="{{ route('books.show', $book) }}" class="group">
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
                                        <div class="aspect-[3/4] bg-gradient-to-br from-purple-100 to-purple-200 relative overflow-hidden">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            </div>
        </main>
    </div>

    <div id="roleRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Request Role Change</h2>
                <button onclick="document.getElementById('roleRequestModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('user.request.role') }}" method="POST">
                @csrf
                <input type="hidden" name="requested_role" value="petugas">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Reason for Request</label>
                    <textarea name="reason" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Explain why you want to become a staff member..." required></textarea>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
</body>
</html>
