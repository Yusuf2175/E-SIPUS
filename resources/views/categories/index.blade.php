@extends('layouts.dashboard')

@section('page-title', 'Category Management')

@section('content')
    <div class="flex-1 -mt-10">
        <div class="p-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Category Management</h1>
                        <p class="text-slate-600 mt-1">Manage book categories for your library</p>
                    </div>
                    <button onclick="openCreateModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-ungu to-primarys hover:from-primarys hover:to-secondrys text-white font-semibold rounded-xl transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Category
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Categories Grid -->
            @if($categories->isEmpty())
                <div class="bg-white rounded-2xl border-2 border-slate-100 p-12 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No Categories Yet</h3>
                    <p class="text-slate-600 mb-6">Start by creating your first book category</p>
                    <button onclick="openCreateModal()" class="inline-flex items-center px-6 py-3 bg-ungu text-white font-semibold rounded-xl hover:bg-primarys transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Category
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-white rounded-2xl border-2 border-slate-100 p-6 hover:border-ungu transition-all hover:shadow-lg group">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-12 h-12 bg-gradient-to-br from-ungu to-primarys rounded-xl flex items-center justify-center shadow-md">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-slate-900 line-clamp-1">{{ $category->name }}</h3>
                                            <p class="text-sm text-slate-500">{{ $category->books_count }} {{ $category->books_count == 1 ? 'book' : 'books' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($category->description)
                                <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ $category->description }}</p>
                            @else
                                <p class="text-sm text-slate-400 italic mb-4">No description</p>
                            @endif

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t border-slate-100">
                                <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')" 
                                        class="flex-1 px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->books_count }})" 
                                        class="flex-1 px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </div>

                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $categories->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Open Create Modal
        function openCreateModal() {
            Swal.fire({
                title: 'Create New Category',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" id="category_name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="e.g., Fiction, Science, History">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                            <textarea id="category_description" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Brief description about this category..."></textarea>
                        </div>
                    </div>
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Create Category',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 font-semibold'
                },
                preConfirm: () => {
                    const name = document.getElementById('category_name').value;
                    if (!name) {
                        Swal.showValidationMessage('Category name is required');
                        return false;
                    }
                    return {
                        name: name,
                        description: document.getElementById('category_description').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitCreateForm(result.value);
                }
            });
        }

        // Submit Create Form
        function submitCreateForm(data) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('categories.store') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const nameField = document.createElement('input');
            nameField.type = 'hidden';
            nameField.name = 'name';
            nameField.value = data.name;
            form.appendChild(nameField);

            if (data.description) {
                const descField = document.createElement('input');
                descField.type = 'hidden';
                descField.name = 'description';
                descField.value = data.description;
                form.appendChild(descField);
            }

            document.body.appendChild(form);
            form.submit();
        }

        // Open Edit Modal
        function openEditModal(id, name, description) {
            Swal.fire({
                title: 'Edit Category',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_category_name" value="${name}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                            <textarea id="edit_category_description" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">${description}</textarea>
                        </div>
                    </div>
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Update Category',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 font-semibold'
                },
                preConfirm: () => {
                    const name = document.getElementById('edit_category_name').value;
                    if (!name) {
                        Swal.showValidationMessage('Category name is required');
                        return false;
                    }
                    return {
                        name: name,
                        description: document.getElementById('edit_category_description').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitEditForm(id, result.value);
                }
            });
        }

        // Submit Edit Form
        function submitEditForm(id, data) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/categories/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            form.appendChild(methodField);

            const nameField = document.createElement('input');
            nameField.type = 'hidden';
            nameField.name = 'name';
            nameField.value = data.name;
            form.appendChild(nameField);

            if (data.description) {
                const descField = document.createElement('input');
                descField.type = 'hidden';
                descField.name = 'description';
                descField.value = data.description;
                form.appendChild(descField);
            }

            document.body.appendChild(form);
            form.submit();
        }

        // Confirm Delete
        function confirmDelete(id, name, booksCount) {
            if (booksCount > 0) {
                Swal.fire({
                    title: 'Cannot Delete Category',
                    html: `
                        <div class="text-left">
                            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-red-800 font-semibold mb-2">⚠️ Category Has Books</p>
                                <p class="text-sm text-red-700">The category "<strong>${name}</strong>" has <strong>${booksCount} book${booksCount > 1 ? 's' : ''}</strong> associated with it.</p>
                            </div>
                            <p class="text-gray-700 text-sm">Please remove all books from this category before deleting it.</p>
                        </div>
                    `,
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'I Understand',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-semibold'
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Delete Category?',
                html: `Are you sure you want to delete the category "<strong>${name}</strong>"?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Show success/error messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8b5cf6',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold'
                }
            });
        @endif
    </script>
@endsection
