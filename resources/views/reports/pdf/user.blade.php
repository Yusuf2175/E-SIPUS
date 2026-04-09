<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data User</title>
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
            background: #10b981;
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
        <h1>LAPORAN DATA USER</h1>
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
            @if($role && $role !== 'all')
            <tr>
                <td><strong>Filter Role:</strong></td>
                <td>{{ ucfirst($role) }}</td>
            </tr>
            @endif
            @if($startDate && $endDate)
            <tr>
                <td><strong>Periode Registrasi:</strong></td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="stats">
        <h3 style="margin-bottom: 10px;">Statistik User</h3>
        <table>
            <tr>
                <th>Total User</th>
                <th>Administrator</th>
                <th>Petugas</th>
                <th>User Biasa</th>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['total'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['admins'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['petugas'] }}</td>
                <td style="text-align: center; font-size: 16px; font-weight: bold;">{{ $stats['users'] }}</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px;">Data User</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama</th>
                <th width="25%">Email</th>
                <th width="10%">Role</th>
                <th width="15%">Tgl Registrasi</th>
                <th width="10%">Peminjaman</th>
                <th width="10%">Review</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $user->borrowings_count }}</td>
                <td style="text-align: center;">{{ $user->reviews_count }}</td>
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
