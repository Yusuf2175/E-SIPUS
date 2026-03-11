<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    public function index()
    {
        // Check if user is petugas or admin
        $user = Auth::user();
        if ($user->role !== 'petugas' && $user->role !== 'admin') {
            abort(403);
        }

        return view('reports.index');
    }

    public function borrowingReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:all,borrowed,returned,overdue',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $status = $request->status ?? 'all';

        // Query borrowings
        $query = Borrowing::with(['book', 'user', 'approvedBy'])
            ->whereBetween('borrowed_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now()->toDateString());
            } else {
                $query->where('status', $status);
            }
        }

        $borrowings = $query->orderBy('borrowed_date', 'desc')->get();

        // Statistics
        $stats = [
            'total' => $borrowings->count(),
            'borrowed' => $borrowings->where('status', 'borrowed')->count(),
            'returned' => $borrowings->where('status', 'returned')->count(),
            'overdue' => $borrowings->where('status', 'borrowed')
                ->filter(function ($b) {
                    return Carbon::parse($b->due_date)->isPast();
                })->count(),
        ];

        // Generate report based on format
        if ($request->format === 'pdf') {
            return $this->generateBorrowingPDF($borrowings, $stats, $startDate, $endDate, $status);
        }
        
        return $this->generateExcel($borrowings, $stats, $startDate, $endDate, $status);
    }

    public function bookReport(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
            'availability' => 'nullable|in:all,available,borrowed',
            'format' => 'required|in:pdf,excel'
        ]);

        $query = Book::with(['addedBy']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->availability === 'available') {
            $query->where('is_available', true);
        } elseif ($request->availability === 'borrowed') {
            $query->where('is_available', false);
        }

        $books = $query->orderBy('title')->get();

        // Statistics
        $stats = [
            'total' => $books->count(),
            'available' => $books->filter(function($book) { return $book->getActualAvailableCopies() > 0; })->count(),
            'borrowed' => $books->filter(function($book) { return $book->getActualAvailableCopies() == 0; })->count(),
            'categories' => $books->pluck('category')->unique()->count(),
        ];

        // Generate report based on format
        if ($request->format === 'pdf') {
            return $this->generateBookPDF($books, $stats, $request->category, $request->availability);
        }
        
        return $this->generateBookExcel($books, $stats, $request->category, $request->availability);
    }

    public function statisticsReport(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Get statistics
        $totalBorrowings = Borrowing::whereBetween('borrowed_date', [$startDate, $endDate])->count();
        $totalReturned = Borrowing::whereBetween('returned_date', [$startDate, $endDate])->count();
        $totalOverdue = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->whereBetween('borrowed_date', [$startDate, $endDate])
            ->count();

        $mostBorrowedBooks = Book::withCount(['borrowings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('borrowed_date', [$startDate, $endDate]);
        }])
            ->orderBy('borrowings_count', 'desc')
            ->limit(10)
            ->get();

        $activeUsers = User::whereHas('borrowings', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('borrowed_date', [$startDate, $endDate]);
        })
            ->withCount(['borrowings' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('borrowed_date', [$startDate, $endDate]);
            }])
            ->orderBy('borrowings_count', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_borrowings' => $totalBorrowings,
            'total_returned' => $totalReturned,
            'total_overdue' => $totalOverdue,
            'most_borrowed_books' => $mostBorrowedBooks,
            'active_users' => $activeUsers,
        ];

        // Generate report based on format
        if ($request->format === 'pdf') {
            return $this->generateStatisticsPDF($stats, $startDate, $endDate, $request->period);
        }
        
        return $this->generateStatisticsExcel($stats, $startDate, $endDate, $request->period);
    }

    public function userReport(Request $request)
    {
        $request->validate([
            'role' => 'nullable|in:all,user,petugas,admin',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'format' => 'required|in:pdf,excel'
        ]);

        $query = User::query();

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by registration date
        if ($request->filled('registration_start') && $request->filled('registration_end')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->registration_start)->startOfDay(),
                Carbon::parse($request->registration_end)->endOfDay()
            ]);
        }

        $users = $query->withCount([
            'borrowings',
            'activeBorrowings',
            'reviews'
        ])->orderBy('created_at', 'desc')->get();

        // Statistics
        $stats = [
            'total' => $users->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'petugas' => $users->where('role', 'petugas')->count(),
            'users' => $users->where('role', 'user')->count(),
            'total_borrowings' => $users->sum('borrowings_count'),
            'active_borrowings' => $users->sum('active_borrowings_count'),
        ];

        // Generate report based on format
        if ($request->format === 'pdf') {
            return $this->generateUserPDF($users, $stats, $request->role, $request->registration_start, $request->registration_end);
        }
        
        return $this->generateUserExcel($users, $stats, $request->role, $request->registration_start, $request->registration_end);
    }

    public function returnReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'return_status' => 'nullable|in:all,on_time,late',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $returnStatus = $request->return_status ?? 'all';

        // Query returned borrowings
        $query = Borrowing::with(['book', 'user', 'returnedBy'])
            ->where('status', 'returned')
            ->whereBetween('returned_date', [$startDate, $endDate]);

        $returns = $query->orderBy('returned_date', 'desc')->get();

        // Filter by return status
        if ($returnStatus === 'on_time') {
            $returns = $returns->filter(function ($return) {
                $dueDate = Carbon::parse($return->due_date);
                $returnDate = Carbon::parse($return->returned_date);
                return $returnDate->lte($dueDate);
            });
        } elseif ($returnStatus === 'late') {
            $returns = $returns->filter(function ($return) {
                $dueDate = Carbon::parse($return->due_date);
                $returnDate = Carbon::parse($return->returned_date);
                return $returnDate->gt($dueDate);
            });
        }

        // Calculate statistics
        $onTimeCount = 0;
        $lateCount = 0;
        $totalLateDays = 0;

        foreach ($returns as $return) {
            $dueDate = Carbon::parse($return->due_date);
            $returnDate = Carbon::parse($return->returned_date);
            
            if ($returnDate->gt($dueDate)) {
                $lateCount++;
                $totalLateDays += $returnDate->diffInDays($dueDate);
            } else {
                $onTimeCount++;
            }
        }

        $stats = [
            'total' => $returns->count(),
            'on_time' => $onTimeCount,
            'late' => $lateCount,
            'avg_late_days' => $lateCount > 0 ? round($totalLateDays / $lateCount, 1) : 0,
        ];

        // Generate report based on format
        if ($request->format === 'pdf') {
            return $this->generateReturnPDF($returns, $stats, $startDate, $endDate, $returnStatus);
        }
        
        return $this->generateReturnExcel($returns, $stats, $startDate, $endDate, $returnStatus);
    }

    private function generateExcel($borrowings, $stats, $startDate, $endDate, $status)
    {
        // For now, return CSV format
        $filename = 'laporan-peminjaman-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($borrowings, $stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN PEMINJAMAN BUKU']);
            fputcsv($file, ['Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            // Statistics
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Peminjaman', $stats['total']]);
            fputcsv($file, ['Sedang Dipinjam', $stats['borrowed']]);
            fputcsv($file, ['Dikembalikan', $stats['returned']]);
            fputcsv($file, ['Terlambat', $stats['overdue']]);
            fputcsv($file, []);
            
            // Table header
            fputcsv($file, ['No', 'Peminjam', 'Buku', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status']);
            
            // Data
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

    private function generateBookExcel($books, $stats, $category, $availability)
    {
        $filename = 'laporan-buku-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($books, $stats) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN DATA BUKU']);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            // Statistics
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Buku', $stats['total']]);
            fputcsv($file, ['Tersedia', $stats['available']]);
            fputcsv($file, ['Dipinjam', $stats['borrowed']]);
            fputcsv($file, ['Kategori', $stats['categories']]);
            fputcsv($file, []);
            
            // Table header
            fputcsv($file, ['No', 'Judul', 'Penulis', 'ISBN', 'Kategori', 'Tahun', 'Status']);
            
            // Data
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

    private function generateStatisticsExcel($stats, $startDate, $endDate, $period)
    {
        $filename = 'laporan-statistik-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN STATISTIK PERPUSTAKAAN']);
            fputcsv($file, ['Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            // Statistics
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Peminjaman', $stats['total_borrowings']]);
            fputcsv($file, ['Total Dikembalikan', $stats['total_returned']]);
            fputcsv($file, ['Total Terlambat', $stats['total_overdue']]);
            fputcsv($file, []);
            
            // Most borrowed books
            fputcsv($file, ['BUKU PALING BANYAK DIPINJAM']);
            fputcsv($file, ['No', 'Judul', 'Penulis', 'Jumlah Peminjaman']);
            foreach ($stats['most_borrowed_books'] as $index => $book) {
                fputcsv($file, [
                    $index + 1,
                    $book->title,
                    $book->author,
                    $book->borrowings_count
                ]);
            }
            fputcsv($file, []);
            
            // Active users
            fputcsv($file, ['PENGGUNA PALING AKTIF']);
            fputcsv($file, ['No', 'Nama', 'Email', 'Jumlah Peminjaman']);
            foreach ($stats['active_users'] as $index => $user) {
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $user->borrowings_count
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateUserExcel($users, $stats, $role, $startDate, $endDate)
    {
        $filename = 'laporan-user-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users, $stats, $role, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Header
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
            
            // Statistics
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total User', $stats['total']]);
            fputcsv($file, ['Administrator', $stats['admins']]);
            fputcsv($file, ['Petugas', $stats['petugas']]);
            fputcsv($file, ['User Biasa', $stats['users']]);
            fputcsv($file, ['Total Peminjaman', $stats['total_borrowings']]);
            fputcsv($file, ['Peminjaman Aktif', $stats['active_borrowings']]);
            fputcsv($file, []);
            
            // Table header
            fputcsv($file, ['No', 'Nama', 'Email', 'Role', 'Tanggal Registrasi', 'Total Peminjaman', 'Peminjaman Aktif', 'Total Review']);
            
            // Data
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

    private function generateUserPDF($users, $stats, $role, $startDate, $endDate)
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

    private function generateBorrowingPDF($borrowings, $stats, $startDate, $endDate, $status)
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

    private function generateBookPDF($books, $stats, $category, $availability)
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

    private function generateStatisticsPDF($stats, $startDate, $endDate, $period)
    {
        $periodLabels = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan'
        ];

        $data = [
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $periodLabels[$period] ?? 'Harian',
            'createdBy' => Auth::user()->name,
            'printDate' => Carbon::now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('reports.pdf.statistics', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-statistik-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    private function generateReturnPDF($returns, $stats, $startDate, $endDate, $returnStatus)
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

    private function generateReturnExcel($returns, $stats, $startDate, $endDate, $returnStatus)
    {
        $filename = 'laporan-pengembalian-' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($returns, $stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN PENGEMBALIAN BUKU']);
            fputcsv($file, ['Periode: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, ['Dibuat oleh: ' . Auth::user()->name]);
            fputcsv($file, ['Tanggal: ' . Carbon::now()->format('d/m/Y H:i')]);
            fputcsv($file, []);
            
            // Statistics
            fputcsv($file, ['STATISTIK']);
            fputcsv($file, ['Total Pengembalian', $stats['total']]);
            fputcsv($file, ['Tepat Waktu', $stats['on_time']]);
            fputcsv($file, ['Terlambat', $stats['late']]);
            fputcsv($file, ['Rata-rata Keterlambatan (hari)', $stats['avg_late_days']]);
            fputcsv($file, []);
            
            // Table header
            fputcsv($file, ['No', 'Peminjam', 'Buku', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Keterlambatan (hari)', 'Status']);
            
            // Data
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
