<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Buku</title>
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
            background: #9333ea;
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
        .signature-section { margin-top: 50px; width: 100%; }
        .signature-section table { width: 100%; }
        .signature-box { text-align: center; padding: 0 20px; }
        .signature-title { font-size: 11px; color: #333; margin-bottom: 5px; }
        .signature-place-date { font-size: 10px; color: #666; margin-bottom: 60px; }
        .signature-line { border-top: 1px solid #333; padding-top: 5px; font-weight: bold; font-size: 11px; }
        .signature-role { font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA BUKU</h1>
        <p>Sistem Informasi Perpustakaan</p>
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
            @if($category)
            <tr>
                <td><strong>Filter Kategori:</strong></td>
                <td>{{ $category }}</td>
            </tr>
            @endif
            @if($availability && $availability !== 'all')
            <tr>
                <td><strong>Filter Ketersediaan:</strong></td>
                <td>{{ $availability === 'available' ? 'Tersedia' : 'Sedang Dipinjam' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="stats">
        <h3 style="margin-bottom: 10px;">Statistik Buku</h3>
        <table>
            <tr>
                <th>Total Buku</th>
                <th>Tersedia</th>
                <th>Dipinjam</th>
                <th>Jumlah Kategori</th>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['total'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['available'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['borrowed'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['categories'] }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Data Buku</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Judul</th>
                <th width="20%">Penulis</th>
                <th width="15%">ISBN</th>
                <th width="15%">Kategori</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $index => $book)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->isbn }}</td>
                <td>{{ $book->category }}</td>
                <td>{{ $book->getActualAvailableCopies() > 0 ? 'Tersedia (' . $book->getActualAvailableCopies() . ')' : 'Dipinjam' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Perpustakaan</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

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
