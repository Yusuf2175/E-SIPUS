@extends('layouts.dashboard')

@section('page-title', 'Library Catalog Management')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Book Inventory Overview</h1>
                <p class="text-slate-600">Comprehensive catalog management and collection administration</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                <a href="{{ route('books.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Book
                </a>
            @endif
        </div>

        {{-- Info wilayah user — dihapus --}}
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('books.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search Books</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Title, author, ISBN..."
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Availability</label>
                    <select name="available" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black">
                        <option value="">All Books</option>
                        <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Available</option>
                    </select>
                </div>

                @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Added By</label>
                        <select name="added_by" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-black">
                            <option value="">All</option>
                            <option value="admin"   {{ request('added_by') == 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ request('added_by') == 'petugas' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center px-6 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Books Grid --}}
    @if($books->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-xl font-semibold text-slate-700 mb-2">No Books Found</h3>
            <p class="text-slate-500 mb-6">Try changing your search filters or reset filters</p>
            <a href="{{ route('books.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white rounded-lg transition">
                Reset Filters
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
                @php $actualAvailableCopies = $book->getActualAvailableCopies(); @endphp
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group">

                    {{-- Cover --}}
                    <div class="aspect-[3/4] bg-gradient-to-br from-purple-100 to-purple-200 relative overflow-hidden">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                 alt="{{ $book->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Availability badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $actualAvailableCopies > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $actualAvailableCopies > 0 ? 'Available' : 'Borrowed' }}
                            </span>
                        </div>

                        {{-- Badge proximity --}}
                        @if($userProvince && $book->region === $userProvince)
                            <div class="absolute bottom-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full shadow">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Wilayah Anda
                                </span>
                            </div>
                        @elseif($userProvince && in_array($book->region, $nearbyProvinces))
                            <div class="absolute bottom-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full shadow">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Sekitar
                                </span>
                            </div>
                        @endif

                        {{-- Edit/Delete (Admin & Petugas) --}}
                        @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                            <div class="absolute top-3 left-3 flex gap-2">
                                <a href="{{ route('books.edit', $book) }}"
                                   class="p-2 bg-white rounded-full shadow-lg hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="confirmDelete({{ $book->id }}, '{{ addslashes($book->title) }}')"
                                        class="p-2 bg-white rounded-full shadow-lg hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $book->id }}" method="POST"
                                      action="{{ route('books.destroy', $book) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 mb-1 line-clamp-2 group-hover:text-purple-600 transition">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-slate-600 mb-2">{{ $book->author }}</p>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">
                                {{ $book->category }}
                            </span>
                            <span class="text-xs text-slate-500">
                                {{ $actualAvailableCopies }}/{{ $book->total_copies }} available
                            </span>
                        </div>

                        @if($book->region)
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-xs text-slate-500 truncate">{{ $book->region }}</span>
                            </div>
                        @endif

                        <a href="{{ route('books.show', $book) }}"
                           class="block w-full text-center px-4 py-2 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white text-sm font-semibold rounded-lg transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($books->hasPages())
            <div class="mt-8">
                {{ $books->links() }}
            </div>
        @endif
    @endif

    <x-book-alert />

    <script>
        function confirmDelete(bookId, bookTitle) {
            Swal.fire({
                title: 'Delete Book?',
                html: `You are about to delete <strong>"${bookTitle}"</strong>.<br><span class="text-sm text-gray-500">This action cannot be undone!</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('delete-form-' + bookId).submit();
                }
            });
        }
    </script>

@endsection
