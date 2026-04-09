
<section id="libary" class="py-20 lg:py-32 bg-white" >
    <div class="max-w-7xl mx-auto px-6 md:px-10">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 bg-purple-100 px-4 py-2 rounded-full mb-6">
                <span class="text-sm font-semibold text-purple-700">Library</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">
                Explore our 
                <span class="bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">curated collection</span>
            </h2>
            <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                From bestsellers to timeless classics — discover your next great read.
            </p>
        </div>

        @if($recommendedBooks->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <p class="text-slate-600">No books available yet</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $bookColors = [
                        ['bg' => 'bg-gradient-to-br from-blue-400 to-blue-600', 'label' => 'bg-blue-500/90'],
                        ['bg' => 'bg-gradient-to-br from-emerald-400 to-emerald-600', 'label' => 'bg-emerald-500/90'],
                        ['bg' => 'bg-gradient-to-br from-orange-400 to-orange-600', 'label' => 'bg-orange-500/90'],
                        ['bg' => 'bg-gradient-to-br from-purple-400 to-purple-600', 'label' => 'bg-purple-500/90'],
                    ];
                @endphp

                @foreach($recommendedBooks->take(4) as $index => $book)
                    @php
                        $color = $bookColors[$index % 4];
                    @endphp
                    <a href="{{ auth()->check() ? route('books.show', $book) : route('login') }}" 
                       class="group relative h-80 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <!-- Background with Book Cover or Gradient -->
                        @if($book->cover_image)
                            <div class="absolute inset-0">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover">
                                <!-- Dark Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
                            </div>
                        @else
                            <!-- Fallback Gradient Background -->
                            <div class="{{ $color['bg'] }} absolute inset-0"></div>
                            
                            <!-- Decorative Pattern -->
                            <div class="absolute inset-0 opacity-10">
                                <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="relative h-full p-6 flex flex-col justify-end">
                            <!-- Bottom Section -->
                            <div class="text-white space-y-3">
                                <!-- Book Title -->
                                <h3 class="text-xl font-bold line-clamp-2 leading-tight">
                                    {{ $book->title }}
                                </h3>
                                
                                <!-- Author -->
                                <p class="text-sm opacity-90 font-medium">
                                    by {{ $book->author }}
                                </p>
                                
                                <!-- Stats -->
                                <div class="flex items-center gap-4 text-sm pt-2">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span class="font-semibold">{{ $book->reviews_count }}</span>
                                    </div>
                                    @if($book->getActualAvailableCopies() > 0)
                                        <div class="flex items-center gap-1.5 text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="font-semibold">Available</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-red-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="font-semibold">Out of Stock</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Hover Arrow -->
                            <div class="absolute bottom-6 right-6 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- View All Button -->
            <div class="text-center mt-12">
                <a href="{{ route('books.index') }}" 
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white text-slate-700 text-base font-semibold border-2 border-slate-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                    Browse All Books
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>

