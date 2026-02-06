@extends('layouts.dashboard')

@section('page-title', 'Profile Settings')

@section('content')
    @if(Auth::user()->isAdmin())
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-gray-900 bg-red-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            Dashboard
        </a>

        <!-- User Management -->
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">User Management</p>
            
            <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.users') ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Kelola Users
            </a>

            <a href="{{ route('admin.role.requests') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.role.requests') ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                Permintaan Role
                @php
                    $pendingCount = \App\Models\RoleRequest::pending()->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>

        <!-- System Management -->
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System Management</p>
            
            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Kelola Buku
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Kategori 
            </a>
        </div>
        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile
        </a>




    @elseif(Auth::user()->isPetugas())
        <!-- Dashboard -->
        <a href="{{ route('petugas.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('petugas.dashboard') ? 'text-gray-900 bg-green-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            Dashboard
        </a>

        <!-- Data Management -->
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Data Management</p>
            
            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Kelola Buku
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Kelola Anggota
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Kategori Buku
            </a>
        </div>

        <!-- Transactions -->
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Transaksi</p>
            
            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Peminjaman
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                Pengembalian
            </a>
        </div>

        <!-- Reports -->
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Laporan</p>
            
            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Laporan Peminjaman
            </a>

            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Statistik Buku
            </a>
        </div>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile
        </a>

    @else
        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'text-gray-900 bg-purple-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            Dashboard
        </a>

        <!-- Profile -->
        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg mb-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profile
        </a>
    @endif
@section('content')
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center">
            @if(Auth::user()->isAdmin())
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
            @elseif(Auth::user()->isPetugas())
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
            @else
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
            @endif
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                <div class="flex items-center mt-2">
                    @if(Auth::user()->isAdmin())
                        <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">Admin</span>
                    @elseif(Auth::user()->isPetugas())
                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Petugas</span>
                    @else
                        <span class="px-3 py-1 text-sm font-medium bg-purple-100 text-purple-800 rounded-full">User</span>
                    @endif
                    <span class="ml-3 text-sm text-gray-500">Bergabung {{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Update Profile Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Informasi Profile</h3>
            </div>
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Update Password</h3>
            </div>
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account Section -->
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-gray-900">Hapus Akun</h3>
        </div>
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
