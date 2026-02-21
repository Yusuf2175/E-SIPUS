@extends('layouts.dashboard')

@section('page-title', 'My Book Collection')

@section('content')
    <div class="relative mb-12">
            @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

        
        <div class="p-8">
            <div class="flex items-start justify-between flex-wrap gap-6">
                <div class="flex-1 min-w-[300px]">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-ungu rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-slate-800">My Collection</h1>
                            <p class="text-slate-500 text-sm mt-1">Your personal reading sanctuary</p>
                        </div>
                    </div>
                    
                    <!-- Mini Stats -->
                    <div class="flex items-center gap-6 mt-6 ml-1">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-ungu rounded-full animate-pulse"></div>
                            <span class="text-2xl font-bold text-ungu">{{ $collections->count() }}</span>
                            <span class="text-slate-600 text-sm">Books Saved</span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-slate-600 text-sm">Curated Collection</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Action Button -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('books.index') }}" class=" px-8 py-4 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold rounded-2xl">
                        <div class="relative flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Discover Books</span>
                        </div>
                    </a>
                    <p class="text-xs text-slate-500 text-center">Explore our library</p>
                </div>
            </div>
        </div>
    </div>


    @if($collections->isEmpty())
        <!-- card menampilkan koleksi belum ada  -->
        <div class="relative">
            <div class="bg-white rounded-3xl shadow-lg p-16 text-center overflow-hidden">
                <div class="relative max-w-lg mx-auto">
                    <div class="relative w-32 h-32 mx-auto mb-8">
                        <div class="relative w-full h-full bg-white rounded-3xl  flex items-center justify-center border-4 border-ungu/20">
                            <svg class="w-16 h-16 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold py-4 px-10 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Explore Library</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Collections Grid -->
        <div class="space-y-5">
            @foreach($collections as $collection)
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-ungu transition-all duration-300">
                    <div class="flex flex-col md:flex-row">
                        <!-- Book Cover - Left Side -->
                        <div class="relative md:w-48 h-64 md:h-auto flex-shrink-0 bg-slate-50">
                            @if($collection->book->cover_image)
                                <img src="{{ asset('storage/' . $collection->book->cover_image) }}" alt="{{ $collection->book->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-ungu to-primarys">
                                    <svg class="w-20 h-20 text-white opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Favorite Heart Icon -->
                            <div class="absolute top-3 left-3">
                                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Book Details - Right Side -->
                        <div class="flex-1 p-6 flex flex-col">
                            <div class="flex-1">
                                <!-- Category Badge -->
                                @if($collection->book->categories && $collection->book->categories->count() > 0)
                                    <div class="inline-flex items-center gap-1.5 bg-cstm text-primarys px-3 py-1 rounded-full text-xs font-semibold mb-3">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $collection->book->categories->first()->name }}
                                    </div>
                                @endif

                                <!-- Title -->
                                <h3 class="text-2xl font-bold text-slate-800 mb-2 line-clamp-2">
                                    {{ $collection->book->title }}
                                </h3>

                                <!-- Author -->
                                <div class="flex items-center gap-2 text-slate-600 mb-4">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $collection->book->author }}</span>
                                </div>

                                <!-- Publisher & Year -->
                                <div class="flex flex-wrap gap-4 text-sm text-slate-500 mb-4">
                                    @if($collection->book->publisher)
                                        <div class="flex items-center gap-1.5">
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                viewBox="0 0 512 512" 
                                                class="w-4 h-4 text-gray-700"
                                                fill="currentColor">
                                                <path d="M213.333333,85.333333 L298.666667,170.666667 L298.666667,426.666667 L42.666667,426.666667 L42.666667,85.333333 L213.333333,85.333333 Z M195.660222,128 L85.333333,128 L85.333333,384 L256,384 L256,188.339779 L195.660222,128 Z M384,33.830111 L473.751611,123.581722 L443.581722,153.751611 L405.333,115.513 L405.333333,256 C405.333333,301.700169 369.408673,339.009683 324.258993,341.2289 L320,341.333333 L320,298.666667 C342.493052,298.666667 360.920856,281.261274 362.549638,259.184264 L362.666667,256 L362.666,115.514 L324.418278,153.751611 L294.248389,123.581722 L384,33.830111 Z"/>
                                            </svg>
                                            <span>{{ $collection->book->publisher }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Availability Status -->
                                <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 px-4 py-2 rounded-lg mb-4">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-700">Available to Borrow</span>
                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full">{{ $collection->book->available_copies }} {{ $collection->book->available_copies > 1 ? 'copies' : 'copy' }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('books.show', $collection->book) }}" class="flex-1 min-w-[200px] inline-flex items-center justify-center gap-2  bg-gradient-to-r from-ungu to-secondrys hover:from-secondrys hover:to-ungu text-white font-semibold px-6 py-3 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>View Details</span>
                                </a>
                                
                                <button type="button" onclick="confirmRemove({{ $collection->id }}, '{{ addslashes($collection->book->title) }}')" class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-red-50 text-slate-700 hover:text-red-600 font-semibold px-6 py-3 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Remove</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden form for deletion -->
                    <form id="remove-form-{{ $collection->id }}" action="{{ route('collections.destroy', $collection) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmRemove(collectionId, bookTitle) {
            Swal.fire({
                title: 'Remove from Collection?',
                html: `Are you sure you want to remove<br><strong>"${bookTitle}"</strong><br>from your collection?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Remove It!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Removing book from collection',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    document.getElementById('remove-form-' + collectionId).submit();
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
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold'
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
                    confirmButton: 'rounded-xl px-6 py-2.5 font-semibold'
                }
            });
        @endif
    </script>
@endsection
