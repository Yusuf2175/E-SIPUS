@extends('layouts.dashboard')

@section('page-title', 'Account Settings')

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-br from-ungu via-purple-600 to-secondrys rounded-3xl shadow-xl p-8 mb-8 relative overflow-hidden">
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center text-ungu font-bold text-3xl shadow-2xl ring-4 ring-white/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">{{ Auth::user()->name }}</h2>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-1.5 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full border border-white/30">
                                {{ Auth::user()->email }}
                            </span>
                            @if(Auth::user()->isAdmin())
                                <span class="px-4 py-1.5 bg-red-500/90 text-white text-sm font-semibold rounded-full">Administrator</span>
                            @elseif(Auth::user()->isPetugas())
                                <span class="px-4 py-1.5 bg-green-500/90 text-white text-sm font-semibold rounded-full">Petugas</span>
                            @else
                                <span class="px-4 py-1.5 bg-blue-500/90 text-white text-sm font-semibold rounded-full">User</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm mb-1">Member Since</p>
                    <p class="text-white font-semibold text-lg">{{ Auth::user()->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            <!-- Update Profile Information -->
            <div class="bg-white rounded-2xl shadow-md p-8 border border-slate-100">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Profile Information</h3>
                        <p class="text-sm text-slate-600">Update your account's profile information and email address</p>
                    </div>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-2xl shadow-md p-8 border border-slate-100">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Update Password</h3>
                        <p class="text-sm text-slate-600">Ensure your account is using a long, random password to stay secure</p>
                    </div>
                </div>
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-2xl shadow-md p-8 border border-red-100">
                <div class="flex items-center gap-3 mb-6 pb-6 border-b border-red-200">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Delete Account</h3>
                        <p class="text-sm text-slate-600">Permanently delete your account and all associated data</p>
                    </div>
                </div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
