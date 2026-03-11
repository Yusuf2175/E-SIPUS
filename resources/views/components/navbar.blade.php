<div>
    <header class="w-full pt-6 fixed top-0 left-0 right-0 z-50 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="rounded-xl bg-white/60 backdrop-blur-sm border  px-6 py-3 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/Logo_Sipus.png') }}" alt="Logo e-SIPUS" class="h-10 w-auto">
                </div>

                <!-- Navigation Menu (Center) -->
                <nav class="hidden md:block">
                    <div class="inline-flex items-center gap-1 text-sm font-medium">
                        <a href="#home" class="px-4 py-2 rounded-full text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition">
                            Home
                        </a>
                        <a href="#features" class="px-4 py-2 rounded-full text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition">
                            Features
                        </a>
                        <a href="#libary" class="px-4 py-2 rounded-full text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition">
                            Library
                        </a>
                        <a href="#about" class="px-4 py-2 rounded-full text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition">
                            About
                        </a>
                    </div>
                </nav>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @auth
        
                        <a href="{{ route('dashboard') }}">
                            <button class="inline-flex items-center px-6 py-2.5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:opacity-90 transition-all duration-300">
                                Go to Dashboard
                            </button>
                        </a>
                    @else
                        <!-- If not logged in -->
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-purple-600 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}">
                            <button class="inline-flex items-center px-6 py-2.5 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:opacity-90 transition-all duration-300">
                                Sign Up
                            </button>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
</div>
