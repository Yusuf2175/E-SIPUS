
    <!-- RECOMMENDED BOOK -->
    <section id="book" class="py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6 md:px-10">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl md:text-2xl font-extrabold tracking-wide text-slate-900">RECOMMENDED BOOKS</h3>
                @auth
                    <a href="{{ route('books.index') }}" class="text-ungu hover:text-primarys font-semibold flex items-center gap-1 transition text-sm">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endauth
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    @foreach($recommendedBooks->take(3) as $book)
                        <a href="{{ auth()->check() ? route('books.show', $book) : route('login') }}" class="group relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                            <!-- Book Cover -->
                            <div class="absolute inset-0">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                                        <svg class="w-24 h-24 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Overlay with Book Info -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#A855F7] via-[#A855F7]/90 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8 translate-y-4 group-hover:translate-y-0">
                                <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-100">
                                    <h4 class="text-white font-extrabold text-2xl mb-2 tracking-wide line-clamp-2">{{ $book->title }}</h4>
                                    <p class="text-purple-100 text-sm mb-1 font-medium">by {{ $book->author }}</p>
                                    @if($book->description)
                                        <p class="text-purple-100 text-sm mb-6 leading-relaxed line-clamp-3">{{ $book->description }}</p>
                                    @else
                                        <p class="text-purple-100 text-sm mb-6 leading-relaxed line-clamp-3">Discover this amazing book in our library collection.</p>
                                    @endif
                                    <button class="w-full py-3 rounded-full bg-white text-ungu text-xs font-bold tracking-[0.2em] uppercase shadow-lg hover:bg-purple-50 transition-colors">
                                        @auth
                                            VIEW DETAILS
                                        @else
                                            LOGIN TO VIEW
                                        @endauth
                                    </button>
                                </div>
                            </div>

                            <!-- Login Required Badge (for guests) -->
                            @guest
                                <div class="absolute top-4 right-4 z-10">
                                    <div class="bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-lg">
                                        <span class="text-xs font-bold text-ungu">Login Required</span>
                                    </div>
                                </div>
                            @endguest
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

         <!-- FOOTER -->
    <footer class="py-8 text-center text-sm text-slate-500 border-t border-slate-200/80">
        &copy; {{ date('Y') }} e-SIPUS. Semua hak cipta dilindungi.
    </footer>
    