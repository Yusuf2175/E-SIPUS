<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->isUser() ? 'Peminjaman Saya' : 'Kelola Peminjaman' }} - E-SIPUS</title>
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
                        {{ Auth::user()->isUser() ? 'Peminjaman Saya' : 'Kelola Peminjaman' }}
                    </h1>
                    <p class="text-slate-600">
                        {{ Auth::user()->isUser() ? 'Riwayat dan status peminjaman buku Anda' : 'Kelola semua peminjaman buku di perpustakaan' }}
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
                            <p class="text-sm text-slate-600">Sedang Dipinjam</p>
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
                            <p class="text-sm text-slate-600">Dikembalikan</p>
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
                            <p class="text-sm text-slate-600">Terlambat</p>
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
                            <p class="text-sm text-slate-600">Total Peminjaman</p>
                        </div>
                    </div>
                @endif

                <!-- Filter Tabs -->
                <div class="bg-white rounded-2xl shadow-sm mb-6">
                    <div class="border-b border-slate-200">
                        <nav class="flex -mb-px">
                            <a href="{{ route('borrowings.index') }}" class="px-6 py-4 text-sm font-medium {{ !request('status') ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Semua
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'borrowed']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'borrowed' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Sedang Dipinjam
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'returned' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Dikembalikan
                            </a>
                            <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" class="px-6 py-4 text-sm font-medium {{ request('status') == 'overdue' ? 'border-b-2 border-purple-600 text-purple-600' : 'text-slate-600 hover:text-slate-800 hover:border-slate-300' }}">
                                Terlambat
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
                        <h3 class="text-xl font-semibold text-slate-700 mb-2">Belum Ada Peminjaman</h3>
                        <p class="text-slate-500 mb-6">{{ Auth::user()->isUser() ? 'Mulai pinjam buku favorit Anda' : 'Belum ada data peminjaman' }}</p>
                        @if(Auth::user()->isUser())
                            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Jelajahi Buku
                            </a>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Buku</th>
                                        @if(!Auth::user()->isUser())
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Peminjam</th>
                                        @endif
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal Pinjam</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Jatuh Tempo</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
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
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $borrowing->borrowed_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700">
                                                {{ $borrowing->due_date->format('d M Y') }}
                                                @if($borrowing->isOverdue())
                                                    <span class="block text-xs text-red-600 font-medium mt-1">
                                                        Terlambat {{ $borrowing->getDaysOverdue() }} hari
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($borrowing->status === 'borrowed')
                                                    @if($borrowing->isOverdue())
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                            Terlambat
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                            Dipinjam
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        Dikembalikan
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('books.show', $borrowing->book) }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                                                        Detail
                                                    </a>
                                                    @if($borrowing->status === 'borrowed' && (Auth::user()->isAdmin() || Auth::user()->isPetugas()))
                                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-700 font-medium text-sm">
                                                                Kembalikan
                                                            </button>
                                                        </form>
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
</body>
</html>
