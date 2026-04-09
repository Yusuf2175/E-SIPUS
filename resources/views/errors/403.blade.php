<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | e-SIPUS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo_E-Sipus.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 flex items-center justify-center p-4">

    <div class="w-full max-w-lg text-center">

        {{-- Icon --}}
        <div class="flex flex-col items-center mb-8">
            {{-- Angka 403 besar --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-purple-500 to-indigo-500 leading-none select-none">4</span>
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-200 rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <span class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-purple-500 to-indigo-500 leading-none select-none">3</span>
            </div>

            {{-- Label --}}
            <span class="px-4 py-1.5 bg-red-100 text-red-600 text-xs font-bold tracking-widest uppercase rounded-full">
                Unauthorized Access
            </span>
        </div>

        {{-- Heading --}}
        <h1 class="text-4xl font-extrabold text-slate-800 mb-3 tracking-tight">
            Akses Ditolak
        </h1>
        <p class="text-slate-500 text-base mb-2">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>

        {{-- Role info --}}
        @auth
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-full text-sm text-slate-600 shadow-sm mb-8">
                <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                Anda login sebagai
                <span class="font-semibold text-purple-700 capitalize">{{ auth()->user()->role }}</span>
                &mdash;
                <span class="text-slate-500">{{ auth()->user()->name }}</span>
            </div>
        @endauth

        {{-- Divider --}}
        <div class="w-16 h-1 bg-gradient-to-r from-purple-400 to-indigo-400 rounded-full mx-auto mb-8"></div>

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                {{-- Kembali ke dashboard sesuai role --}}
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard Admin
                    </a>
                @elseif(auth()->user()->isPetugas())
                    <a href="{{ route('petugas.dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard Petugas
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard Saya
                    </a>
                @endif

                <button onclick="history.back()"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 hover:border-purple-300 text-slate-700 font-semibold rounded-xl transition-all hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </button>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Login
                </a>
            @endauth
        </div>

        {{-- Footer --}}
        <p class="mt-10 text-xs text-slate-400">
            e-SIPUS &copy; {{ date('Y') }} &mdash; Sistem Informasi Perpustakaan
        </p>
    </div>

</body>
</html>
