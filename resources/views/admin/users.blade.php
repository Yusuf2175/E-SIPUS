@extends('layouts.dashboard')

@section('page-title', 'Manage Users')

@section('content')
    <!-- Header with Search -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manage Users</h1>
                <p class="text-sm text-slate-600 mt-1">View and manage all library members</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                type="text" 
                id="searchInput"
                placeholder="Search members by name or email..." 
                class="w-full pl-12 pr-4 py-3 bg-white text-slate-700 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
            >
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-slate-200">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-base font-semibold text-slate-900">Library Members</h2>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Joined</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-slate-50 user-row">
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $users->firstItem() + $index }}</td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                    <span class="text-xs text-slate-500">ID: {{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $user->email }}</td>
                        
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $user->created_at->format('d M Y') }}</td>
                        
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                                <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-state">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900">No members found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    
                    <!-- No Search Results -->
                    <tr id="noResults" class="hidden">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900 mb-1">No results found</p>
                                <p class="text-xs text-slate-500">Try searching with different keywords</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.user-row');
            const noResults = document.getElementById('noResults');
            const emptyState = document.querySelector('.empty-state');
            let visibleCount = 0;
            
            tableRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (name.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (searchValue && visibleCount === 0) {
                noResults.classList.remove('hidden');
                if (emptyState) emptyState.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (emptyState && visibleCount === 0 && !searchValue) {
                    emptyState.style.display = '';
                }
            }
        });

        function confirmDeleteUser(userId, userName) {
            Swal.fire({
                title: 'Delete Member?',
                html: `Are you sure you want to delete "<strong>${userName}</strong>"?<br><br>
                       <div class="text-left bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                           <p class="text-sm text-red-800"><strong>⚠️ Warning:</strong></p>
                           <ul class="text-xs text-red-700 mt-2 space-y-1">
                               <li>• Member must not have active borrowings</li>
                               <li>• This action cannot be undone</li>
                           </ul>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-user-form-' + userId).submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10b981'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>
@endsection
