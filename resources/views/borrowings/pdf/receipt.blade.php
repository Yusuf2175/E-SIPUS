<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @php
        $isReturned  = $borrowing->status === 'returned';
        $docType     = $isReturned ? 'Pengembalian' : 'Peminjaman';
        $docNumber   = str_pad($borrowing->id, 6, '0', STR_PAD_LEFT);
    @endphp
    <title>Bukti {{ $docType }} #{{ $docNumber }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 4px solid #9333ea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #9333ea;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        .header h2 {
            color: #64748b;
            font-size: 18px;
            margin: 0;
        }
        .receipt-number {
            text-align: right;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #f1f5f9;
            padding: 10px 15px;
            font-weight: bold;
            color: #1e293b;
            border-left: 4px solid #9333ea;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 40%;
            padding: 8px 15px;
            color: #64748b;
            font-size: 11px;
        }
        .info-value {
            display: table-cell;
            padding: 8px 15px;
            font-weight: bold;
            color: #1e293b;
        }
        .book-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .book-title {
            color: #9333ea;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .signature-section {
            margin-top: 60px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .signature-line {
            border-top: 2px solid #1e293b;
            margin-top: 80px;
            padding-top: 10px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-borrowed {
            background: #dcfce7;
            color: #166534;
        }
        .status-returned {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-pending-return {
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>e-SIPUS</h1>
        <h2>Bukti {{ $docType }} Buku</h2>
    </div>

    <!-- Receipt Number -->
    <div class="receipt-number">
        <strong>No. {{ $docType }}:</strong> #{{ $docNumber }}
    </div>

    <!-- Borrower Information -->
    <div class="section">
        <div class="section-title">Informasi {{ $isReturned ? 'Pengembalian' : 'Peminjam' }}</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Peminjam</div>
                <div class="info-value">{{ $borrowing->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $borrowing->user->email }}</div>
            </div>
        </div>
    </div>

    <!-- Book Information -->
    <div class="section">
        <div class="section-title">Informasi Buku</div>
        <div class="book-info">
            <div class="book-title">{{ $borrowing->book->title }}</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Penulis</div>
                    <div class="info-value">{{ $borrowing->book->author }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">ISBN</div>
                    <div class="info-value">{{ $borrowing->book->isbn }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Penerbit</div>
                    <div class="info-value">{{ $borrowing->book->publisher ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">{{ $borrowing->book->category }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowing Details -->
    <div class="section">
        <div class="section-title">Detail {{ $docType }}</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Jatuh Tempo</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d F Y') }}</div>
            </div>
            @if($isReturned && $borrowing->returned_date)
            <div class="info-row">
                <div class="info-label">Tanggal Dikembalikan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->returned_date)->format('d F Y') }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @if($borrowing->status === 'approved' || $borrowing->status === 'borrowed')
                        <span class="status-badge status-borrowed">✓ Dipinjam</span>
                    @elseif($borrowing->status === 'pending_return')
                        <span class="status-badge status-pending-return">⏳ Menunggu Konfirmasi Pengembalian</span>
                    @elseif($borrowing->status === 'returned')
                        <span class="status-badge status-returned">✓ Dikembalikan</span>
                    @endif
                </div>
            </div>
            @if($borrowing->approvedBy)
            <div class="info-row">
                <div class="info-label">{{ $isReturned ? 'Dikembalikan ke' : 'Disetujui Oleh' }}</div>
                <div class="info-value">{{ $borrowing->approvedBy->name }}</div>
            </div>
            @endif
            @if($isReturned && $borrowing->return_reason)
            <div class="info-row">
                <div class="info-label">Catatan Pengembalian</div>
                <div class="info-value">{{ $borrowing->return_reason }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p>{{ $isReturned ? 'Yang Mengembalikan' : 'Peminjam' }}</p>
            <div class="signature-line">
                {{ $borrowing->user->name }}
            </div>
        </div>
        <div class="signature-box">
            <p>{{ $isReturned ? 'Petugas Penerima' : 'Petugas yang Menyetujui' }}</p>
            <div class="signature-line">
                {{ $borrowing->approvedBy->name ?? '_______________' }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem e-SIPUS</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
