{{--Hero section--}}
<section id="home" class="relative min-h-[100vh] w-full flex items-center pb-20" style="background: linear-gradient(135deg, rgb(238, 242, 255), rgb(224, 231, 255), rgb(245, 243, 255), rgb(237, 233, 254), rgb(224, 231, 255), rgb(238, 242, 255));">
    <div class="relative max-w-7xl mx-auto px-6 md:px-10 pt-28 pb-0 md:pt-32 w-full">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-purple-200 shadow-sm mb-8">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-sm font-semibold text-slate-700">Smart Digital Library Platform</span>
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            </div>

            {{-- Main Heading --}}
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-800 mb-6">
                Your Library, 
                <span class="bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Reimagined</span>
                <br>for the Digital Age
            </h1>

            {{-- Description --}}
            <p class="mt-6 text-lg md:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                e-SIPUS is a modern digital library management system that makes discovering, borrowing, and managing books effortless and delightful.
            </p>

            {{-- CTA Buttons --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#libary" class="group inline-flex items-center px-8 py-4 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-base font-semibold shadow-lg shadow-purple-500/30 hover:shadow-2xl hover:shadow-purple-500/50 hover:scale-70 hover:-translate-y-1 transition-all duration-300">
                     <span>Explore books</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                </a>
                <a href="#features" class="inline-flex items-center px-8 py-4   text-slate-700 text-base font-semibold hover:scale-105 transition-all duration-300">
                    learn more
                </a>
            </div>

            {{-- Statistics --}}
            <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-3xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ number_format($totalBooks) }}+
                    </div>
                    <div class="mt-2 text-sm text-slate-600 font-medium">Books</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ number_format($totalUsers) }}+
                    </div>
                    <div class="mt-2 text-sm text-slate-600 font-medium">Users</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ $totalCategories }}+
                    </div>
                    <div class="mt-2 text-sm text-slate-600 font-medium">Categories</div>
                </div>
            </div>

            {{-- Dashboard Mockup with Skeleton Loader --}}
            <div class="mt-20 max-w-6xl mx-auto relative">
                <!-- Decorative Elements -->
                <div class="absolute -left-20 top-20 w-32 h-32 bg-purple-200/30 rounded-full blur-3xl"></div>
                <div class="absolute -right-20 bottom-20 w-40 h-40 bg-indigo-200/30 rounded-full blur-3xl"></div>
                
                <!-- Character Image Right -->
                <div class="hidden lg:block absolute -left-40 bottom-0 w-[22rem] z-20">
                    <img src="{{ asset('assets/Character_Sipus_1.png') }}" alt="Character Sipus" class="w-full h-auto drop-shadow-2xl">
                </div>

                <div class="max-w-4xl mx-auto relative z-10">
                <div class="bg-white rounded-3xl overflow-hidden border px-5 py-6">
                    {{-- Browser Header --}}
                    <div class=" px-4 py-3 flex items-center gap-3 ">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        {{-- Search Bar --}}
                        <div class="flex-1 flex items-center gap-2 bg-indigo-300/10 rounded-full px-4 py-2 ">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <div class=" flex-1 h-3 bg-slate-200 rounded animate-pulse"></div>
                        </div>
                    </div>

                    {{-- Dashboard Content --}}
                    <div class="p-5 bg-gradient-to-br from-slate-50/30 to-white/50 h-[480px]">
                        <div class="flex gap-5 h-full">
                            {{-- Sidebar --}}
                            <div class="w-40 space-y-1.5">
                                <div class=" rounded-lg px-4 py-3 text-slate-500 text-sm font-medium">
                                    Dashboard
                                </div>
                                <div class="bg-purple-100/60 rounded-lg px-4 py-3 text-purple-600 text-sm font-semibold">
                                    Books
                                </div>
                                <div class=" rounded-lg px-4 py-3 text-slate-500 text-sm font-medium">
                                    Borrowing
                                </div>
                                <div class=" rounded-lg px-4 py-3 text-slate-500 text-sm font-medium">
                                    Return
                                </div>
                                <div class=" rounded-lg px-4 py-3 text-slate-500 text-sm font-medium">
                                    Reports
                                </div>
                            </div>

                            {{-- Main Content with Skeleton Loader --}}
                            <div class="flex-1">
                                <div class="grid grid-cols-4 gap-3 h-full content-start">
                                    {{-- Card 1 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse"></div>
                                    </div>

                                    {{-- Card 2 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.1s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.1s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.1s;"></div>
                                    </div>

                                    {{-- Card 3 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.2s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.2s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.2s;"></div>
                                    </div>

                                    {{-- Card 4 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.3s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.3s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.3s;"></div>
                                    </div>

                                    {{-- Card 5 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.4s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.4s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.4s;"></div>
                                    </div>

                                    {{-- Card 6 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.5s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.5s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.5s;"></div>
                                    </div>

                                    {{-- Card 7 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.6s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.6s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.6s;"></div>
                                    </div>

                                    {{-- Card 8 --}}
                                    <div class="group bg-indigo-300/10 rounded-xl p-4 border border-slate-200/50 hover:border-purple-300/50 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer">
                                        <div class="h-32 bg-gradient-to-br from-slate-300/60 to-slate-200/70 rounded-lg mb-3 animate-pulse" style="animation-delay: 0.7s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-3/4 mb-2 animate-pulse" style="animation-delay: 0.7s;"></div>
                                        <div class="h-2.5 bg-slate-200/70 rounded w-1/2 animate-pulse" style="animation-delay: 0.7s;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
