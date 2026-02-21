<!-- Petugas Sidebar Component -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-slate-200 overflow-y-auto z-40">
    <!-- Logo/Header -->
    <div class="h-16 flex items-center px-6 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <x-application-logo></x-application-logo>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 pb-8">
        <!-- Dashboard -->
        <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('petugas.dashboard') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- Manajemen Perpustakaan -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Management</p>
            
            <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('books.index') || request()->routeIs('books.show') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Manage Books
            </a>

            <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('borrowings.index') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Manage Borrowings
            </a>

            <a href="{{ route('petugas.users') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('petugas.users') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Manage Students
            </a>

            <a href="{{ route('borrowings.return.page') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('borrowings.return.page') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                </svg>
                My Book Returns
            </a>
            
            <a href="{{ route('collections.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('collections.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                My Collection
            </a>
            
            <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('reviews.*') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                Reviews & Ratings
            </a>
        </div>

        <!-- Laporan -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Reports</p>
            
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('reports.*') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Reports
            </a>
        </div>

        <!-- Akun -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Account</p>
            
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('profile.edit') ? 'bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition text-red-600 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</aside>
