{{--Hero section--}}
<section id="home" class="relative min-h-[100vh] w-full flex items-center bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/background.png') }}');">
    {{-- untuk shadow kiri background cover --}}
    <div class="absolute inset-0 bg-gradient-to-r from-white/85 via-white/50 to-transparent pointer-events-none"></div>
    {{-- untuk shadow bawah backgroud cover --}}
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-white pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-6 md:px-10 pt-28 pb-16 md:pt-32 md:pb-24 w-full">
        <div class="max-w-xl">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-800 drop-shadow-sm flex flex-col text-start gap-3">
               <div class="text-slate-600">Let’s <span class="text-secondrys">organize knowledge,</span> digitally</div>
               <span class="text-2xl text-slate-600 ">A smarter way to manage your library.</span>
            </h1>
            <p class="mt-4 text-sm md:text-base text-slate-600 leading-relaxed max-w-md">
                A web-based digital library system that helps manage books, members, borrowing and returning books efficiently and modernly.
            </p>
            <div class="mt-8">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3.5 rounded-full bg-secondrys text-white text-sm font-bold uppercase shadow-lg shadow-indigo-500/30 hover:bg-[#5f13af] transition-all duration-500">
                        Go to Dashboard 
                    </a>
                @else
                    <button onclick="openModal('loginModal')" class="inline-flex items-center px-8 py-3.5 rounded-full bg-secondrys text-white text-sm font-bold uppercase shadow-lg shadow-indigo-500/30 hover:bg-[#5f13af] transition-all duration-500">
                        Explore E-SIPUS
                    </button>
                @endauth
            </div>
        </div>
    </div>
</section>
