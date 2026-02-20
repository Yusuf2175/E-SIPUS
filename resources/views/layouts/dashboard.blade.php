<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('page-title', 'Dashboard')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo_E-Sipus.png') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @if(Auth::user()->role === 'admin')
            <x-sidebar-admin />
        @elseif(Auth::user()->role === 'petugas')
            <x-sidebar-petugas />
        @else
            <x-sidebar-user />
        @endif

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Bar -->
            <aside class="px-12 py-5 sticky top-0 z-30">
                <header class="rounded-3xl bg-ungu shadow-lg">
                    <div class="flex items-center justify-between px-5 py-1">
                        
                        <!-- Search Bar -->
                        <div class="flex-1 max-w-3xl relative" x-data="searchComponent()">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input 
                                    type="text" 
                                    x-model="query"
                                    @focus="showResults = true"
                                    @click.away="showResults = false"
                                    @keydown.escape="showResults = false"
                                    @keydown.down.prevent="navigateDown"
                                    @keydown.up.prevent="navigateUp"
                                    @keydown.enter.prevent="selectResult"
                                    placeholder="Search pages in dashboard..." 
                                    class="w-full pl-10 pr-4 py-2.5 bg-cstm rounded-xl text-sm text-slate-700 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-primarys/20 focus:border-primarys transition">
                            </div>

                            <!-- hasil pencarian berdasarkan query input pada search bar yang menampilkan dropdown data pages yang cocok -->
                            <div 
                                x-show="showResults && filteredResults.length > 0"
                                x-transition
                                class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-96 overflow-y-auto z-50">
                                <template x-for="(result, index) in filteredResults" :key="index">
                                    <a 
                                        :href="result.url"
                                        @mouseenter="selectedIndex = index"
                                        :class="selectedIndex === index ? 'bg-primarys/10 border-l-4 border-primarys' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                        class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 last:border-0 transition">
                                        <div 
                                            :class="result.color"
                                            class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="result.icon"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800" x-text="result.title"></p>
                                            <p class="text-xs text-slate-500" x-text="result.description"></p>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </template>
                            </div>

                            <!-- untuk menampilkan pesan ketika tidak ada hasil pencarian -->
                            <div 
                                x-show="showResults && query.length > 0 && filteredResults.length === 0"
                                x-transition
                                class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-xl p-6 z-50 text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-slate-600 font-medium">No results found</p>
                                <p class="text-xs text-slate-400 mt-1">Try searching for "<span x-text="query" class="font-semibold"></span>"</p>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3 px-3 py-2  rounded-lg transition cursor-pointer">
                                <div class="w-10 h-10 bg-cstm rounded-full flex items-center justify-center text-gray-700 font-bold ring-2 ring-primarys/20">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-white font-bold flex items-center gap-1">
                                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                                        {{ ucfirst(Auth::user()->role) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </aside>
            <!-- Page Content -->
            <main class="p-12">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function searchComponent() {
            return {
                query: '',
                showResults: false,
                selectedIndex: 0,
                pages: [
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'petugas' || Auth::user()->role === 'user')
                    {
                        title: 'Dashboard',
                        description: 'View dashboard overview',
                        url: '{{ route("admin.dashboard") }}',
                        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                        color: 'bg-primarys',
                        keywords: ['dashboard', 'home', 'overview', 'main']
                    },
                    {
                        title: 'Manage Users',
                        description: 'View and manage all users',
                        url: '{{ route("admin.users") }}',
                        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
                        color: 'bg-secondrys',
                        keywords: ['users', 'manage users', 'user management', 'accounts', 'members']
                    },
                    @endif
                    {
                        title: 'Manage Books',
                        description: 'Browse and manage book collection',
                        url: '{{ route("books.index") }}',
                        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                        color: 'bg-blue-500',
                        keywords: ['books', 'manage books', 'library', 'collection', 'catalog', 'buku']
                    },
                    {
                        title: 'Manage Borrowings',
                        description: 'Track and manage book borrowings',
                        url: '{{ route("borrowings.index") }}',
                        icon: 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        color: 'bg-amber-500',
                        keywords: ['borrowings', 'loans', 'borrowed books', 'transactions', 'peminjaman']
                    },
                    {
                        title: 'Book Returns',
                        description: 'Manage book returns',
                        url: '{{ route("borrowings.return.page") }}',
                        icon: 'M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z',
                        color: 'bg-green-500',
                        keywords: ['returns', 'return books', 'give back', 'pengembalian']
                    },
                    {
                        title: 'My Collection',
                        description: 'View your favorite books',
                        url: '{{ route("collections.index") }}',
                        icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                        color: 'bg-pink-500',
                        keywords: ['collection', 'favorites', 'saved books', 'wishlist', 'koleksi']
                    },
                    {
                        title: 'Reviews & Ratings',
                        description: 'View and manage book reviews',
                        url: '{{ route("reviews.index") }}',
                        icon: 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                        color: 'bg-indigo-500',
                        keywords: ['reviews', 'ratings', 'comments', 'feedback', 'ulasan']
                    },
                    @if(Auth::user()->role === 'admin')
                    {
                        title: 'Generate Reports',
                        description: 'Create and view system reports',
                        url: '{{ route("reports.index") }}',
                        icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        color: 'bg-purple-500',
                        keywords: ['reports', 'analytics', 'statistics', 'data', 'laporan']
                    },
                    @endif
                    {
                        title: 'Profile Settings',
                        description: 'Manage your account settings',
                        url: '{{ route("profile.edit") }}',
                        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        color: 'bg-slate-500',
                        keywords: ['profile', 'settings', 'account', 'preferences', 'profil']
                    }
                ],
                get filteredResults() {
                    if (this.query.length === 0) return [];
                    
                    const searchTerm = this.query.toLowerCase();
                    return this.pages.filter(page => {
                        return page.title.toLowerCase().includes(searchTerm) ||
                               page.description.toLowerCase().includes(searchTerm) ||
                               page.keywords.some(keyword => keyword.includes(searchTerm));
                    });
                },
                navigateDown() {
                    if (this.selectedIndex < this.filteredResults.length - 1) {
                        this.selectedIndex++;
                    }
                },
                navigateUp() {
                    if (this.selectedIndex > 0) {
                        this.selectedIndex--;
                    }
                },
                selectResult() {
                    if (this.filteredResults.length > 0) {
                        window.location.href = this.filteredResults[this.selectedIndex].url;
                    }
                }
            }
        }
    </script>
</body>
</html>
