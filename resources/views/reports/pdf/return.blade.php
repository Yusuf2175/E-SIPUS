<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengembalian Buku</title>
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
            background: #f97316;
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
        .badge-ontime {
            background: #10b981;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-late {
            background: #ef4444;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-section table {
            width: 100%;
        }
        .signature-box {
            text-align: center;
            padding: 0 20px;
        }
        .signature-title {
            font-size: 11px;
            color: #333;
            margin-bottom: 5px;
        }
        .signature-place-date {
            font-size: 10px;
            color: #666;
            margin-bottom: 60px;
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENGEMBALIAN BUKU</h1>
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
        <h3 style="margin-bottom: 10px;">Statistik Pengembalian</h3>
        <table>
            <tr>
                <th>Total Pengembalian</th>
                <th>Tepat Waktu</th>
                <th>Terlambat</th>
                <th>Rata-rata Keterlambatan</th>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['total'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['on_time'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['late'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['avg_late_days'] }} hari</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Data Pengembalian</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Peminjam</th>
                <th width="22%">Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Jatuh Tempo</th>
                <th width="12%">Tgl Kembali</th>
                <th width="10%">Keterlambatan</th>
                <th width="9%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $index => $return)
            @php
                $dueDate = \Carbon\Carbon::parse($return->due_date);
                $returnDate = \Carbon\Carbon::parse($return->returned_date);
                $lateDays = $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
                $isLate = $lateDays > 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $return->user->name }}</td>
                <td>{{ $return->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($return->borrowed_date)->format('d/m/Y') }}</td>
                <td>{{ $dueDate->format('d/m/Y') }}</td>
                <td>{{ $returnDate->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $lateDays > 0 ? $lateDays . ' hari' : '-' }}</td>
                <td style="text-align: center;">
                    @if($isLate)
                        <span class="badge-late">Terlambat</span>
                    @else
                        <span class="badge-ontime">Tepat Waktu</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Perpustakaan</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <table>
            <tr>
                <td class="signature-box" width="33%">
                    <div class="signature-title">Dibuat Oleh,</div>
                    <div class="signature-place-date">{{ now()->format('d F Y') }}</div>
                    <div class="signature-line">{{ $createdBy }}</div>
                    <div class="signature-role">Petugas / Admin</div>
                </td>
                <td class="signature-box" width="33%">
                    <div class="signature-title">Diperiksa Oleh,</div>
                    <div class="signature-place-date">{{ now()->format('d F Y') }}</div>
                    <div class="signature-line">___________________</div>
                    <div class="signature-role">Kepala Perpustakaan</div>
                </td>
                <td class="signature-box" width="33%">
                    <div class="signature-title">Mengetahui,</div>
                    <div class="signature-place-date">{{ now()->format('d F Y') }}</div>
                    <div class="signature-line">___________________</div>
                    <div class="signature-role">Pimpinan</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
