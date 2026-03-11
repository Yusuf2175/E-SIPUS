<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportGeneratorService
{
    /**
     * Generate borrowing report PDF.
     */
    public function generateBorrowingPDF($borrowings, $stats, Carbon $startDate, Carbon $endDate, string $status)
    {
        $statusLabels = [
            'all' => 'Semua Status',
            'borrowed' => 'Sedang Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue' => 'Terlambat'
        ];

        $data = [
            'borrowings' => $borrowings,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusLabel' => $statusLabels[$status] ?? 'Semua Status',
            'createdBy' => Auth::user()->name,
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('reports.pdf.borrowing', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-peminjaman-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate borrowing report Excel/CSV.
     */
    public function generateBorrowingExcel($borrowings, $stats, Carbon $startDate, Carbon $endDate)
    {
        $filename = 'laporan-peminjaman-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($borrowings, $stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['LAPORAN PEMINJAMAN BUKU']);
            fputcsv($file, ['Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Peminjaman', $stats['total']]);
            fputcsv($file, ['Sedang Dipinjam', $stats['borrowed']]);
            fputcsv($file, ['Dikembalikan', $stats['returned']]);
            fputcsv($file, ['Terlambat', $stats['overdue']]);
            fputcsv($file, []);
            
            fputcsv($file, ['No', 'Peminjam', 'Buku', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status']);
            
            foreach ($borrowings as $index => $borrowing) {
                fputcsv($file, [
                    $index + 1,
                    $borrowing->user->name,
                    $borrowing->book->title,
                    Carbon::parse($borrowing->borrowed_date)->format('d/m/Y'),
                    Carbon::parse($borrowing->due_date)->format('d/m/Y'),
                    $borrowing->returned_date ? Carbon::parse($borrowing->returned_date)->format('d/m/Y') : '-',
                    $borrowing->status === 'borrowed' ? 'Dipinjam' : 'Dikembalikan'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate book report PDF.
     */
    public function generateBookPDF($books, $stats, $category, $availability)
    {
        $data = [
            'books' => $books,
            'stats' => $stats,
            'category' => $category,
            'availability' => $availability,
            'createdBy' => Auth::user()->name,
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('reports.pdf.book', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-buku-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate book report Excel/CSV.
     */
    public function generateBookExcel($books, $stats)
    {
        $filename = 'laporan-buku-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($books, $stats) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['LAPORAN DATA BUKU']);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Buku', $stats['total']]);
            fputcsv($file, ['Tersedia', $stats['available']]);
            fputcsv($file, ['Dipinjam', $stats['borrowed']]);
            fputcsv($file, ['Kategori', $stats['categories']]);
            fputcsv($file, []);
            
            fputcsv($file, ['No', 'Judul', 'Penulis', 'ISBN', 'Kategori', 'Tahun', 'Status']);
            
            foreach ($books as $index => $book) {
                fputcsv($file, [
                    $index + 1,
                    $book->title,
                    $book->author,
                    $book->isbn,
                    $book->category,
                    $book->publication_year,
                    $book->is_available ? 'Tersedia' : 'Dipinjam'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate user report PDF.
     */
    public function generateUserPDF($users, $stats, $role, $startDate, $endDate)
    {
        $data = [
            'users' => $users,
            'stats' => $stats,
            'role' => $role,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'createdBy' => Auth::user()->name,
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('reports.pdf.user', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-user-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate user report Excel/CSV.
     */
    public function generateUserExcel($users, $stats, $role, $startDate, $endDate)
    {
        $filename = 'laporan-user-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users, $stats, $role, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['LAPORAN DATA USER']);
            if ($startDate && $endDate) {
                fputcsv($file, ['Periode Registrasi: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')]);
            }
            if ($role && $role !== 'all') {
                fputcsv($file, ['Filter Role: ' . ucfirst($role)]);
            }
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total User', $stats['total']]);
            fputcsv($file, ['Administrator', $stats['admins']]);
            fputcsv($file, ['Petugas', $stats['petugas']]);
            fputcsv($file, ['User Biasa', $stats['users']]);
            fputcsv($file, ['Total Peminjaman', $stats['total_borrowings']]);
            fputcsv($file, ['Peminjaman Aktif', $stats['active_borrowings']]);
            fputcsv($file, []);
            
            fputcsv($file, ['No', 'Nama', 'Email', 'Role', 'Tanggal Registrasi', 'Total Peminjaman', 'Peminjaman Aktif', 'Total Review']);
            
            foreach ($users as $index => $user) {
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    Carbon::parse($user->created_at)->format('d/m/Y'),
                    $user->borrowings_count,
                    $user->active_borrowings_count,
                    $user->reviews_count
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate return report PDF.
     */
    public function generateReturnPDF($returns, $stats, Carbon $startDate, Carbon $endDate, string $returnStatus)
    {
        $statusLabels = [
            'all' => 'Semua',
            'on_time' => 'Tepat Waktu',
            'late' => 'Terlambat'
        ];

        $data = [
            'returns' => $returns,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusLabel' => $statusLabels[$returnStatus] ?? 'Semua',
            'createdBy' => Auth::user()->name,
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('reports.pdf.return', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-pengembalian-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate return report Excel/CSV.
     */
    public function generateReturnExcel($returns, $stats, Carbon $startDate, Carbon $endDate)
    {
        $filename = 'laporan-pengembalian-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($returns, $stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['LAPORAN PENGEMBALIAN BUKU']);
            fputcsv($file, ['Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Pengembalian', $stats['total']]);
            fputcsv($file, ['Tepat Waktu', $stats['on_time']]);
            fputcsv($file, ['Terlambat', $stats['late']]);
            fputcsv($file, ['Rata-rata Keterlambatan (hari)', $stats['avg_late_days']]);
            fputcsv($file, []);
            
            fputcsv($file, ['No', 'Peminjam', 'Buku', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Keterlambatan (hari)', 'Status']);
            
            foreach ($returns as $index => $return) {
                $dueDate = Carbon::parse($return->due_date);
                $returnDate = Carbon::parse($return->returned_date);
                $lateDays = $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
                $status = $lateDays > 0 ? 'Terlambat' : 'Tepat Waktu';
                
                fputcsv($file, [
                    $index + 1,
                    $return->user->name,
                    $return->book->title,
                    Carbon::parse($return->borrowed_date)->format('d/m/Y'),
                    $dueDate->format('d/m/Y'),
                    $returnDate->format('d/m/Y'),
                    $lateDays > 0 ? $lateDays : '-',
                    $status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
