<!-- Admin Sidebar Component -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-slate-200 overflow-y-auto z-40">
    <!-- Logo/Header -->
    <div class="h-16 flex items-center px-6 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <span class="text-xl font-bold text-slate-800">E-SIPUS</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- User Management -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">User Management</p>
            
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.users') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Kelola Users
            </a>

            <a href="{{ route('admin.role.requests') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.role.requests') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Permintaan Role
            </a>
        </div>

        <!-- Library Management -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Perpustakaan</p>
            
            <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('books.*') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Kelola Buku
            </a>

            <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('borrowings.*') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Kelola Peminjaman
            </a>
        </div>

        <!-- Account -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Akun</p>
            
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('profile.edit') ? 'bg-red-50 text-red-700' : 'text-slate-700 hover:bg-slate-50' }}">
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
