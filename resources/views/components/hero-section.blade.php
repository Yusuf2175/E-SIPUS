{{-- Hero section: background full width & full ke atas (di belakang navbar) --}}
<section id="home" class="relative min-h-[100vh] w-full flex items-center bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/background.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-r from-white/85 via-white/50 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-white pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-6 md:px-10 pt-28 pb-16 md:pt-32 md:pb-24 w-full">
        <div class="max-w-xl">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-800 drop-shadow-sm flex flex-col text-start gap-3">
               <div class="text-slate-600">Let’s <span class="text-secondrys">organize knowledge,</span> digitally</div>
               <span class="text-2xl text-slate-600 ">A smarter way to manage your library.</span>
            </h1>
            <p class="mt-4 text-sm md:text-base text-slate-600 leading-relaxed max-w-md">
                Sistem perpustakaan digital berbasis web yang membantu pengelolaan buku, anggota, peminjaman, dan pengembalian secara efisien dan modern
            </p>
            <div class="mt-8">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3.5 rounded-full bg-[#0ea5e9] text-white text-sm font-bold tracking-[0.15em] uppercase shadow-lg shadow-sky-500/30 hover:bg-[#0284c7] transition-all duration-200">
                        Go to Dashboard
                    </a>
                @else
                    <button onclick="openModal('registerModal')" class="inline-flex items-center px-8 py-3.5 rounded-full bg-secondrys text-white text-sm font-bold uppercase shadow-lg shadow-indigo-500/30 hover:bg-[#5f13af] transition-all duration-200">
                        Explore E-SIPUS
                    </button>
                @endauth
            </div>
        </div>
    </div>
</section>
