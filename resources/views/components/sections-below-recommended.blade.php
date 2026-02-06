
    <!-- RECOMMENDED BOOK -->
    <section id="book" class="py-16 lg:py-20">
        <div class="max-w-6xl mx-auto px-6 md:px-10">
            <h3 class="text-xl md:text-2xl font-extrabold tracking-wide text-slate-900 mb-8">RECOMMENDED BOOK</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @for ($i = 0; $i < 3; $i++)
                    <div class="group relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                        <div class="absolute inset-0 bg-slate-200">
                            <div class="w-full h-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                                <span class="text-purple-300 font-bold text-4xl">BOOK</span>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#A855F7] via-[#A855F7]/90 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8 translate-y-4 group-hover:translate-y-0">
                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-100">
                                <h4 class="text-white font-extrabold text-2xl mb-2 tracking-wide line-clamp-1">Judul Buku {{ $i+1 }}</h4>
                                <p class="text-purple-100 text-sm mb-6 leading-relaxed line-clamp-3">Deskripsi singkat mengenai isi buku ini yang menarik untuk dibaca oleh pengunjung perpustakaan.</p>
                                <button class="w-full py-3 rounded-full bg-white text-ungu text-xs font-bold tracking-[0.2em] uppercase shadow-lg hover:bg-purple-50 transition-colors">Open Book</button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

         <!-- FOOTER -->
    <footer class="py-8 text-center text-sm text-slate-500 border-t border-slate-200/80">
        &copy; {{ date('Y') }} e-SIPUS. Semua hak cipta dilindungi.
    </footer>
    