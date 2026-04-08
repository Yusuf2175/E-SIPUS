<div>
   <!-- FOOTER -->
<footer id="footer" class="py-12 bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-6 md:px-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">e</span>
                    </div>
                    <span class="text-xl font-bold text-white">e-SIPUS</span>
                </div>
                <p class="text-sm leading-relaxed max-w-md">
                    A modern digital library management system that makes discovering, borrowing, and managing books effortless and delightful.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#home" class="hover:text-purple-400 transition-colors">Home</a></li>
                    <li><a href="#features" class="hover:text-purple-400 transition-colors">Features</a></li>
                    <li><a href="#libary" class="hover:text-purple-400 transition-colors">Library</a></li>
                    <li><a href="{{ route('books.index') }}" class="hover:text-purple-400 transition-colors">Books</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-semibold mb-4">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Jl. Perpustakaan No. 10, Kec. Sukamaju, Kota Bandung, Jawa Barat 40123</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Brlyinex2@esipus.com</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>+62 123 4567 890</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} e-SIPUS. All rights reserved.</p>
        </div>
    </div>
</footer>
</div>