@extends('layouts.dashboard')

@section('page-title', 'Manage Staff')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manage Staff</h1>
                <p class="text-sm text-slate-600 mt-1">View and manage all library staff members</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-200 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Staff
            </button>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                type="text" 
                id="searchInput"
                placeholder="Search staff by name or email..." 
                class="w-full pl-12 pr-4 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm"
            >
        </div>
    </div>

    <!-- Staff Table Card -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-base font-semibold text-slate-900">All Staff Members</h2>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($petugas as $index => $staff)
                    <tr class="hover:bg-slate-50 transition-colors staff-row">
                        <!-- Number -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center w-8 h-8 bg-slate-100 rounded-lg">
                                <span class="text-sm font-bold text-slate-700">{{ $index + 1 }}</span>
                            </div>
                        </td>

                        <!-- Staff Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $staff->name }}</p>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        </svg>
                                        Staff
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-slate-700">{{ $staff->email }}</span>
                            </div>
                        </td>

                        <!-- Joined Date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-slate-600">{{ $staff->created_at->format('d M Y') }}</span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ $staff->id }}, '{{ addslashes($staff->name) }}', '{{ $staff->email }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="confirmDelete({{ $staff->id }}, '{{ addslashes($staff->name) }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg border border-red-200 hover:bg-red-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-state">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-900 mb-1">No staff members found</p>
                                <button onclick="openAddModal()" class="mt-3 text-sm text-purple-600 hover:text-purple-700 font-semibold">
                                    Add your first staff member
                                </button>
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
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800">Add New Staff</h3>
            </div>

            {{-- Form asli hanya berisi hidden inputs — Chrome tidak bisa autofill --}}
            <form id="addForm" action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf
                <input type="hidden" id="add_real_name"     name="name">
                <input type="hidden" id="add_real_email"    name="email">
                <input type="hidden" id="add_real_password" name="password">
            </form>

            {{-- Field visible di luar form --}}
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                    <input id="add_vis_name" type="text"
                           autocomplete="off"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input id="add_vis_email" type="text"
                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly')"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <input id="add_vis_password" type="text"
                               autocomplete="off"
                               style="-webkit-text-security:disc"
                               class="w-full px-4 pr-12 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                        <button type="button" onclick="toggleAddPwd()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="add_eye_on" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="add_eye_off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <button type="button" onclick="submitAddForm()"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg transition">
                        Add Staff
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800">Edit Staff</h3>
            </div>

            {{-- Form asli hanya berisi hidden inputs --}}
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_real_name"     name="name">
                <input type="hidden" id="edit_real_email"    name="email">
                <input type="hidden" id="edit_real_password" name="password">
            </form>

            {{-- Field visible di luar form --}}
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                    <input id="edit_vis_name" type="text"
                           autocomplete="off"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input id="edit_vis_email" type="text"
                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly')"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">New Password <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <div class="relative">
                        <input id="edit_vis_password" type="text"
                               autocomplete="off"
                               style="-webkit-text-security:disc"
                               class="w-full px-4 pr-12 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-slate-900">
                        <button type="button" onclick="toggleEditPwd()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="edit_eye_on" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="edit_eye_off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <button type="button" onclick="submitEditForm()"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-xl hover:shadow-lg transition">
                        Update Staff
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (hidden) -->
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.staff-row');
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

        function openAddModal() {
            // Reset semua field visible saat modal dibuka
            document.getElementById('add_vis_name').value     = '';
            document.getElementById('add_vis_email').value    = '';
            document.getElementById('add_vis_password').value = '';
            document.getElementById('addModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('add_vis_name').focus(), 100);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function submitAddForm() {
            const name  = document.getElementById('add_vis_name').value.trim();
            const email = document.getElementById('add_vis_email').value.trim();
            const pwd   = document.getElementById('add_vis_password').value;
            if (!name || !email || !pwd) {
                alert('Semua field wajib diisi.');
                return;
            }
            document.getElementById('add_real_name').value     = name;
            document.getElementById('add_real_email').value    = email;
            document.getElementById('add_real_password').value = pwd;
            document.getElementById('addForm').submit();
        }

        let addShowPwd = false;
        function toggleAddPwd() {
            addShowPwd = !addShowPwd;
            const el = document.getElementById('add_vis_password');
            el.style.webkitTextSecurity = addShowPwd ? 'none' : 'disc';
            document.getElementById('add_eye_on').classList.toggle('hidden', addShowPwd);
            document.getElementById('add_eye_off').classList.toggle('hidden', !addShowPwd);
        }

        function openEditModal(id, name, email) {
            document.getElementById('editForm').action = `/admin/petugas/${id}`;
            document.getElementById('edit_vis_name').value     = name;
            document.getElementById('edit_vis_email').value    = email;
            document.getElementById('edit_vis_password').value = '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function submitEditForm() {
            const name  = document.getElementById('edit_vis_name').value.trim();
            const email = document.getElementById('edit_vis_email').value.trim();
            const pwd   = document.getElementById('edit_vis_password').value;
            if (!name || !email) {
                alert('Nama dan email wajib diisi.');
                return;
            }
            document.getElementById('edit_real_name').value     = name;
            document.getElementById('edit_real_email').value    = email;
            document.getElementById('edit_real_password').value = pwd; // boleh kosong
            document.getElementById('editForm').submit();
        }

        let editShowPwd = false;
        function toggleEditPwd() {
            editShowPwd = !editShowPwd;
            const el = document.getElementById('edit_vis_password');
            el.style.webkitTextSecurity = editShowPwd ? 'none' : 'disc';
            document.getElementById('edit_eye_on').classList.toggle('hidden', editShowPwd);
            document.getElementById('edit_eye_off').classList.toggle('hidden', !editShowPwd);
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Delete Staff?',
                html: `Are you sure you want to delete staff "<strong>${name}</strong>"?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/admin/petugas/${id}`;
                    form.submit();
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
