<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->isUser() ? 'My Borrowings' : 'Manage Borrowings' }} - E-SIPUS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        @if(auth()->user()->isAdmin())
            <x-sidebar-admin />
        @elseif(auth()->user()->isPetugas())
            <x-sidebar-petugas />
        @else
            <x-sidebar-user />
        @endif

        <div class="flex-1 ml-64">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">
                        {{ Auth::user()->isUser() ? 'My Borrowings' : 'Manage Borrowings' }}
                    </h1>
                    <p class="text-slate-600">
                        {{ Auth::user()->isUser() ? 'History and status of your book borrowings' : 'Manage all book borrowings in the library' }}
                    </p>
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

                <!-- CTA Return Books (for User) -->
                @if(Auth::user()->isUser())
                    @php
                        $activeBorrowingsCount = \App\Models\Borrowing::where('user_id', Auth::id())
                            ->where('status', 'borrowed')
                            ->count();
                    @endphp
                    
                    @if($activeBorrowingsCount > 0)
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl shadow-lg p-6 mb-6 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Have Books to Return?</h3>
                                        <p class="text-white/90">You have {{ $activeBorrowingsCount }} borrowed book{{ $activeBorrowingsCount > 1 ? 's' : '' }}. Return on time to avoid fines!</p>
                                    </div>
                                </div>
                                <a href="{{ route('borrowings.return.page') }}" class="flex-shrink-0 inline-flex items-center px-6 py-3 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition-colors duration-200 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path>
                                    </svg>
                                    Return Books
                                </a>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Statistics Cards (for Admin/Petugas) -->
                @if(Auth::user()->isAdmin() || Auth::user()->isPetugas())
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['active'] ?? 0 }}</p>
                            <p class="text-sm text-slate-600">Currently Borrowed</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['returned'] ?? 0 }}</p>
                            <p class="text-sm text-slate-600">Returned</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-red-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['overdue'] ?? 0 }}</p>
                            <p class="text-sm text-slate-600">Overdue</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['total'] ?? 0 }}</p>
                            <p class="text-sm text-slate-600">Total Borrowings</p>
                        </div>
                    </div>
                @endif

                <!-- Filter Tabs -->
                <div class="bg-white rounded-2xl shadow-sm mb-6">
                    <div class="border-b border-slate-200">
                        <nav class="flex -mb-px">
                            <a href="{{ route('borrowings.index') }}" class="px-6 py-4 text-sm font-medium {{ !request('status') ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                All
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'borrowed' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Currently Borrowed
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'returned' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Returned
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'overdue' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Overdue
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Borrowings List -->
                @if($borrowings->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <svg class="w-24 h-24 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-slate-700 mb-2">No Borrowings Yet</h3>
                        <p class="text-slate-500 mb-6">{{ Auth::user()->isUser() ? 'Start borrowing your favorite books' : 'No borrowing data available' }}</p>
                        @if(Auth::user()->isUser())
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Browse Books
                            </a>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Book</th>
                                        @if(!Auth::user()->isUser())
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Borrower</th>
                                        @endif
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Borrow Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($borrowings as $borrowing)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                                        @if($borrowing->book->cover_image)
                                                            <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover rounded-lg">
                                                        @else
                                                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-slate-800">{{ $borrowing->book->title }}</p>
                                                        <p class="text-sm text-slate-600">{{ $borrowing->book->author }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            @if(!Auth::user()->isUser())
                                                <td class="px-6 py-4">
                                                    <p class="font-medium text-slate-800">{{ $borrowing->user->name }}</p>
                                                    <p class="text-sm text-slate-600">{{ $borrowing->user->email }}</p>
                                                    @if($borrowing->user->role === 'admin')
                                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded bg-red-100 text-red-700">Admin</span>
                                                    @elseif($borrowing->user->role === 'petugas')
                                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-700">Petugas</span>
                                                    @else
                                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-700">User</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $borrowing->borrowed_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $borrowing->due_date->format('d M Y') }}
                                                @if($borrowing->isOverdue())
                                                    <span class="block text-xs text-red-600 font-medium mt-1">
                                                        Overdue {{ $borrowing->getDaysOverdue() }} day{{ $borrowing->getDaysOverdue() > 1 ? 's' : '' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($borrowing->status === 'borrowed')
                                                    @if($borrowing->isOverdue())
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                            Overdue
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                            Borrowed
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        Returned
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('books.show', $borrowing->book) }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                                                        Details
                                                    </a>
                                                    {{-- return button only for admin and petugas --}}
                                                    @if($borrowing->status === 'borrowed' && (Auth::user()->isAdmin() || Auth::user()->isPetugas()))
                                                        <button type="button" onclick="openReturnModal({{ $borrowing->id }})" class="text-green-600 hover:text-green-700 font-medium text-sm">
                                                            Return
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($borrowings->hasPages())
                        <div class="mt-6">
                            {{ $borrowings->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function openReturnModal(borrowingId) {
            Swal.fire({
                title: 'Book Return',
                html: createReturnForm(),
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Return Book',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                preConfirm: () => {
                    return validateAndGetFormData();
                }
            }).then((result) => {
                // Jika user klik "Return Book" dan form valid
                if (result.isConfirmed) {
                    submitReturnForm(borrowingId, result.value);
                }
            });
        }

        // menampilkan pop up untuk return option
        function createReturnForm() {
            return `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Return Reason <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                                <input type="radio" name="return_reason" value="normal" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Normal</span>
                                    <p class="text-xs text-gray-600">Book returned in good condition</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition">
                                <input type="radio" name="return_reason" value="user_missing" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">User Missing</span>
                                    <p class="text-xs text-gray-600">User cannot be contacted</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition">
                                <input type="radio" name="return_reason" value="book_damaged" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Book Damaged</span>
                                    <p class="text-xs text-gray-600">Book returned in damaged condition</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-500 hover:bg-red-50 transition">
                                <input type="radio" name="return_reason" value="book_lost" class="mt-1">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-800">Book Lost</span>
                                    <p class="text-xs text-gray-600">Book cannot be returned</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label for="return_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea 
                            id="return_notes" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Add notes if needed...">
                        </textarea>
                    </div>
                </div>
            `;
        }

        // Fungsi untuk validasi dan ambil data dari form
        function validateAndGetFormData() {
            const selectedReason = document.querySelector('input[name="return_reason"]:checked');
            const notes = document.getElementById('return_notes').value;
            
            if (!selectedReason) {
                Swal.showValidationMessage('Please select a return reason');
                return false;
            }
            
            // Return data yang akan disubmit
            return {
                return_reason: selectedReason.value,
                return_notes: notes
            };
        }

        // Fungsi untuk submit form return ke server
        function submitReturnForm(borrowingId, formData) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/borrowings/' + borrowingId + '/return';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';
            form.appendChild(methodField);
            
            const reasonField = document.createElement('input');
            reasonField.type = 'hidden';
            reasonField.name = 'return_reason';
            reasonField.value = formData.return_reason;
            form.appendChild(reasonField);
            
            if (formData.return_notes) {
                const notesField = document.createElement('input');
                notesField.type = 'hidden';
                notesField.name = 'return_notes';
                notesField.value = formData.return_notes;
                form.appendChild(notesField);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>

</body>
</html>
