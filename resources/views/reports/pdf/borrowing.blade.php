<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 0;
        }
        .stats {
            margin-bottom: 20px;
        }
        .stats table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats th, .stats td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .stats th {
            background: #4a5568;
            color: white;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th {
            background: #3b82f6;
            color: white;
            padding: 10px 5px;
            text-align: left;
            font-size: 11px;
        }
        .data-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU</h1>
        <p>Sistem Informasi Perpustakaan</p>
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="150"><strong>Dibuat oleh:</strong></td>
                <td>{{ $createdBy }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ $printDate }}</td>
            </tr>
            <tr>
                <td><strong>Filter Status:</strong></td>
                <td>{{ $statusLabel }}</td>
            </tr>
        </table>
    </div>

    <div class="stats">
        <h3 style="margin-bottom: 10px;">Statistik Peminjaman</h3>
        <table>
            <tr>
                <th>Total Peminjaman</th>
                <th>Sedang Dipinjam</th>
                <th>Dikembalikan</th>
                <th>Terlambat</th>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['total'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['borrowed'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['returned'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['overdue'] }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Data Peminjaman</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Peminjam</th>
                <th width="25%">Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Jatuh Tempo</th>
                <th width="12%">Tgl Kembali</th>
                <th width="14%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $index => $borrowing)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $borrowing->user->name }}</td>
                <td>{{ $borrowing->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d/m/Y') }}</td>
                <td>{{ $borrowing->returned_date ? \Carbon\Carbon::parse($borrowing->returned_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $borrowing->status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Perpustakaan</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
