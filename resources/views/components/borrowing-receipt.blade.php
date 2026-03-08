@props(['borrowing'])

<div id="printContent-{{ $borrowing->id }}" class="hidden">
    <div class="max-w-4xl mx-auto bg-white p-8">
        <!-- Header -->
        <div class="text-center border-b-4 border-purple-500 pb-6 mb-6">
            <h1 class="text-3xl font-bold text-purple-600 mb-2">e-SIPUS</h1>
            <h2 class="text-xl text-slate-600">Bukti Peminjaman Buku</h2>
        </div>

        <!-- Receipt Number -->
        <div class="text-right text-slate-600 mb-6">
            <strong>No. Peminjaman:</strong> #{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}
        </div>

        <!-- Borrower Information -->
        <div class="mb-6">
            <div class="bg-slate-100 px-4 py-2 font-bold text-slate-800 border-l-4 border-purple-500 mb-4">
                Informasi Peminjam
            </div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <div>
                    <p class="text-sm text-slate-500">Nama Peminjam</p>
                    <p class="font-semibold text-slate-800">{{ $borrowing->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Email</p>
                    <p class="font-semibold text-slate-800">{{ $borrowing->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Book Information -->
        <div class="mb-6">
            <div class="bg-slate-100 px-4 py-2 font-bold text-slate-800 border-l-4 border-purple-500 mb-4">
                Informasi Buku
            </div>
            <div class="bg-slate-50 p-4 rounded-lg">
                <h3 class="text-xl font-bold text-purple-600 mb-3">{{ $borrowing->book->title }}</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Penulis</p>
                        <p class="font-semibold text-slate-800">{{ $borrowing->book->author }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">ISBN</p>
                        <p class="font-semibold text-slate-800">{{ $borrowing->book->isbn }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Penerbit</p>
                        <p class="font-semibold text-slate-800">{{ $borrowing->book->publisher ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Kategori</p>
                        <p class="font-semibold text-slate-800">{{ $borrowing->book->category }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing Details -->
        <div class="mb-6">
            <div class="bg-slate-100 px-4 py-2 font-bold text-slate-800 border-l-4 border-purple-500 mb-4">
                Detail Peminjaman
            </div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <div>
                    <p class="text-sm text-slate-500">Tanggal Pinjam</p>
                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Tanggal Jatuh Tempo</p>
                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="font-semibold">
                        @if($borrowing->status === 'approved' || $borrowing->status === 'borrowed')
                            <span class="text-green-600">✓ Dipinjam</span>
                        @elseif($borrowing->status === 'returned')
                            <span class="text-blue-600">✓ Dikembalikan</span>
                        @endif
                    </p>
                </div>
                @if($borrowing->approvedBy)
                <div>
                    <p class="text-sm text-slate-500">Disetujui Oleh</p>
                    <p class="font-semibold text-slate-800">{{ $borrowing->approvedBy->name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 mt-12 print-only">
            <div class="text-center text-gray-800">
                <p class="mb-16">Borrower</p>
                <div class="border-t-2 border-slate-800 pt-2">
                    <p class="font-bold">{{ $borrowing->user->name }}</p>
                </div>
            </div>
            <div class="text-center text-gray-800">
                <p class="mb-16">Authorized Staff</p>
                <div class="border-t-2 border-slate-800 pt-2">
                    <p class="font-bold">{{ $borrowing->approvedBy->name ?? '_______________' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
