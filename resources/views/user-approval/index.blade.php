@extends('layouts.dashboard')

@section('page-title', 'User Approvals')

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-1">User Registration Approvals</h1>
        <p class="text-slate-500">Review and manage new user registration requests</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-400">
            <p class="text-sm text-slate-500 mb-1">Pending</p>
            <p class="text-4xl font-bold text-slate-800">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Awaiting review</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-400">
            <p class="text-sm text-slate-500 mb-1">Approved</p>
            <p class="text-4xl font-bold text-slate-800">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Active accounts</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-red-400">
            <p class="text-sm text-slate-500 mb-1">Rejected</p>
            <p class="text-4xl font-bold text-slate-800">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Declined registrations</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6 overflow-hidden">
        <nav class="flex border-b border-slate-200 overflow-x-auto">
            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <a href="{{ route('user-approvals.index', ['status' => $key]) }}"
                   class="px-6 py-4 text-sm font-semibold whitespace-nowrap transition
                          {{ $status === $key ? 'border-b-2 border-purple-600 text-purple-600 bg-purple-50/50' : 'text-slate-600 hover:text-purple-600 hover:bg-slate-50' }}">
                    {{ $label }}
                    @if($key !== 'all' && $stats[$key] > 0)
                        <span class="ml-1.5 px-2 py-0.5 text-xs font-bold rounded-full
                            {{ $key === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($key === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ $stats[$key] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Session messages --}}
    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    @if($users->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-slate-500 font-medium">No {{ $status !== 'all' ? $status : '' }} registrations found</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            {{-- User Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Location --}}
                            <td class="px-6 py-4">
                                @if($user->province || $user->city)
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $user->city ? $user->city . ', ' : '' }}{{ $user->province ?? '-' }}</span>
                                    </div>
                                    @if($user->address)
                                        <p class="text-xs text-slate-400 mt-0.5 ml-5 line-clamp-1">{{ $user->address }}</p>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- Registered --}}
                            <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                {{ $user->created_at->format('d M Y') }}
                                <p class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</p>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($user->account_status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                        Pending
                                    </span>
                                @elseif($user->account_status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Rejected
                                    </span>
                                    @if($user->rejection_reason)
                                        <p class="text-xs text-red-400 mt-1">{{ Str::limit($user->rejection_reason, 40) }}</p>
                                    @endif
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                @if($user->account_status === 'pending')
                                    <div class="flex items-center gap-2">
                                        {{-- Approve --}}
                                        <form action="{{ route('user-approvals.approve', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <button type="button"
                                                onclick="openRejectModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </button>
                                    </div>
                                @elseif($user->account_status === 'rejected')
                                    {{-- Re-approve --}}
                                    <form action="{{ route('user-approvals.approve', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-semibold rounded-xl transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Re-approve
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">No action needed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Reject Modal --}}
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Reject Registration</h3>
            <p class="text-sm text-slate-500 mb-5" id="rejectModalName"></p>

            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Reason <span class="text-slate-400 font-normal">(optional)</span>
                    </label>
                    <textarea name="rejection_reason" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-slate-700 resize-none"
                              placeholder="Provide a reason for rejection..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                        Confirm Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(userId, userName) {
            document.getElementById('rejectModalName').textContent = 'Rejecting: ' + userName;
            document.getElementById('rejectForm').action = '/user-approvals/' + userId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>

@endsection
