<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // For now, only support Excel/CSV format
        return $this->generateExcel($borrowings, $stats, $startDate, $endDate, $status);
    }

    public function bookReport(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
            'availability' => 'nullable|in:all,available,borrowed',
            'format' => 'required|in:pdf,excel'
        ]);

        $query = Book::with(['addedByUser']);

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
            'available' => $books->where('is_available', true)->count(),
            'borrowed' => $books->where('is_available', false)->count(),
            'categories' => $books->pluck('category')->unique()->count(),
        ];

        // For now, only support Excel/CSV format
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

        // For now, only support Excel/CSV format
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

        // Generate Excel/CSV format
        return $this->generateUserExcel($users, $stats, $request->role, $request->registration_start, $request->registration_end);
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
}
