<div>
    <header class="w-full pt-6 fixed top-0 left-0 right-0 z-50 px-4">
        <div class="max-w-6xl mx-auto flex justify-center">
            <div class="w-full max-w-4xl rounded-full bg-white/20 backdrop-blur-md border border-white/30 shadow-lg px-4 md:px-6 py-3 flex items-center justify-between gap-3 md:gap-6 inset-shadow-sm inset-gray-950/50">
                <div class="flex items-center flex-1">
                    <div class="flex items-center gap-4 md:gap-6">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <img src="{{ asset('assets/Logo_Sipus.png') }}" alt="Logo e-SIPUS" class="h-9 md:h-11 w-auto">
                        </div>
                        <!-- Tabbar -->
                        <nav class="hidden md:block">
                            <div class="inline-flex items-center gap-1 text-[13px] font-semibold tracking-[0.12em] uppercase">
                                <a href="#home" class="px-4 py-2 rounded-full transition  group flex flex-col items-center  ">
                                    Home
                                    <span class=" relative h-[3px] -bottom-2 w-0 bg-ungu 
                                                rounded-full transition-all duration-500 
                                                group-hover:w-10 group-hover:left-0">
                                    </span> 
                                </a>
                                <a href="#about" class="px-4 py-2 rounded-full transition group flex flex-col items-center">
                                    About
                                    <span class=" relative h-[3px] -bottom-2 w-0 bg-ungu 
                                            rounded-full transition-all duration-500 
                                            group-hover:w-10 group-hover:left-0">
                                    </span>
                                </a>
                                <a href="#categories" class="px-4 py-2 rounded-full transition group flex flex-col items-center">
                                    Category
                                     <span class=" relative h-[3px] -bottom-2 w-0 bg-ungu 
                                            rounded-full transition-all duration-500 
                                            group-hover:w-10 group-hover:left-0">
                                    </span>
                                </a>
                                <a href="#book" class="px-4 py-2 rounded-full transition group flex flex-col items-center">
                                    Book
                                     <span class=" relative h-[3px] -bottom-2 w-0 bg-ungu 
                                            rounded-full transition-all duration-500 
                                            group-hover:w-10 group-hover:left-0">
                                    </span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Sign up dan avatar -->
                <div class="shrink-0 flex items-center gap-2">
                    @auth
                        <!-- Jika sudah login, tampilkan dashboard link -->
                        <a href="{{ route('dashboard') }}">
                            <button class="inline-flex items-center px-5 py-2.5 rounded-full bg-ungu text-white text-xs font-semibold tracking-[0.18em] uppercase hover:bg-secondrys transition shadow-md">
                                Dashboard
                            </button>
                        </a>
                        <div class="w-9 h-9 rounded-full bg-white/40 flex items-center justify-center overflow-hidden ring-2 ring-ungu">
                            <span class="text-xs font-bold text-gray-700">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                    @else
                        <a href="{{ route('register') }}">
                            <button class="inline-flex items-center px-5 py-2.5 rounded-full bg-secondrys text-white text-xs font-semibold tracking-[0.18em] uppercase hover:bg-[#9333EA] transition shadow-md">
                                Sign Up
                            </button>
                        </a>
                        <a href="{{ route('login') }}">
                            <div class="w-9 h-9 rounded-full bg-primarys/20 flex items-center justify-center overflow-hidden cursor-pointer hover:ring-2 hover:ring-purple-400 transition">
                                <img src="{{ asset('assets/Character_avatar.png') }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
</div>
