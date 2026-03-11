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
                    <li>Email: Brlyinex2@esipus.com</li>
                    <li>Phone: +62 123 4567 890</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} e-SIPUS. All rights reserved.</p>
        </div>
    </div>
</footer>
</div>