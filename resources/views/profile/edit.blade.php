@extends('layouts.dashboard')

@section('page-title', 'Account Settings')

@section('content')
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Profile Management</h2>
                <p class="text-gray-600 mt-1">Update your personal information and security settings</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        <!-- Update Profile Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
