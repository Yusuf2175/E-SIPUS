
<!-- CTA SECTION -->
<section class="py-20 lg:py-28 -mt-20 bg-white">
    <div class="max-w-5xl mx-auto px-6 md:px-10">
        <!-- CTA Card -->
        <div class="relative bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-500 rounded-full blur-3xl"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 px-8 md:px-16 py-16 md:py-20 text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/20">
                    <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-white">Smart Digital Library Platform</span>
                </div>

                <!-- Main Heading -->
                <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                    Start using e-SIPUS
                    <br>
                    <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">today</span>
                </h2>

                <!-- Description -->
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Join thousands of libraries worldwide. Transform your library management with the most modern platform available.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="group inline-flex items-center px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm text-white text-base font-semibold border-2 border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300">
                            <span>Get Started</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="group inline-flex items-center px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm text-white text-base font-semibold border-2 border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300">
                            <span>Get Started</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endauth
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm text-white text-base font-semibold border-2 border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300">
                        Sign Up Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
