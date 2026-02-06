<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        @if(auth()->user()->isAdmin())
            <x-sidebar-admin />
        @elseif(auth()->user()->isPetugas())
            <x-sidebar-petugas />
        @else
            <x-sidebar-user />
        @endif

        <div class="flex-1 ml-64">
            <div class="p-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800 mb-2">Perpustakaan Digital</h1>
                            <p class="text-slate-600">Jelajahi koleksi buku digital kami</p>
                        </div>
                        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                            <button class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Buku
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                    <form method="GET" action="{{ route('books.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Cari Buku</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, penulis, ISBN..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Kategori</label>
                                <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Availability Filter -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Ketersediaan</label>
                                <select name="available" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Semua Buku</option>
                                    <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Tersedia</option>
                                </select>
                            </div>

                            <!-- Added By Filter -->
                            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Ditambahkan Oleh</label>
                                    <select name="added_by" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Semua</option>
                                        <option value="admin" {{ request('added_by') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="petugas" {{ request('added_by') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Books Grid -->
                @if($books->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <svg class="w-24 h-24 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-slate-700 mb-2">Tidak Ada Buku Ditemukan</h3>
                        <p class="text-slate-500 mb-6">Coba ubah filter pencarian Anda atau reset filter</p>
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Reset Filter
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($books as $book)
                            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group">
                                <div class="aspect-[3/4] bg-gradient-to-br from-purple-100 to-purple-200 relative overflow-hidden">
                                    @if($book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Availability Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $book->isAvailable() ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                            {{ $book->isAvailable() ? 'Tersedia' : 'Dipinjam' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-bold text-slate-800 mb-1 line-clamp-2 group-hover:text-purple-600 transition">{{ $book->title }}</h3>
                                    <p class="text-sm text-slate-600 mb-2">{{ $book->author }}</p>
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">{{ $book->category }}</span>
                                        <span class="text-xs text-slate-500">{{ $book->available_copies }}/{{ $book->total_copies }} tersedia</span>
                                    </div>
                                    
                                    <a href="{{ route('books.show', $book) }}" class="block w-full text-center px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($books->hasPages())
                        <div class="mt-8">
                            {{ $books->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</body>
</html>
