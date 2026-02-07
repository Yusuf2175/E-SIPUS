# Fitur Pengembalian Buku untuk Petugas & Admin

## Overview

Menambahkan fitur pengembalian buku untuk petugas dan admin, sama seperti yang sudah ada untuk user biasa. Fitur ini memungkinkan petugas dan admin untuk mengelola peminjaman buku mereka sendiri.

## Fitur yang Ditambahkan

### 1. Menu Sidebar Baru

**Lokasi**: Di bawah menu "Kelola Peminjaman"

#### Sidebar Petugas (`resources/views/components/sidebar-petugas.blade.php`)

- ✅ Menu "Pengembalian Buku Saya" dengan icon upload
- ✅ Highlight hijau saat aktif
- ✅ Route: `borrowings.return.page`

#### Sidebar Admin (`resources/views/components/sidebar-admin.blade.php`)

- ✅ Menu "Pengembalian Buku Saya" dengan icon upload
- ✅ Highlight merah saat aktif
- ✅ Route: `borrowings.return.page`

### 2. CTA Banner di Dashboard

#### Dashboard Petugas (`resources/views/dashboards/petugas.blade.php`)

- ✅ Banner gradient hijau muncul jika petugas memiliki buku yang dipinjam
- ✅ Menampilkan jumlah buku yang dipinjam
- ✅ Tombol "Kembalikan Buku" mengarah ke halaman pengembalian
- ✅ Posisi: Setelah statistics cards, sebelum recent borrowings

#### Dashboard Admin (`resources/views/dashboards/admin.blade.php`)

- ✅ Banner gradient merah muncul jika admin memiliki buku yang dipinjam
- ✅ Menampilkan jumlah buku yang dipinjam
- ✅ Tombol "Kembalikan Buku" mengarah ke halaman pengembalian
- ✅ Posisi: Setelah statistics cards, sebelum staff statistics

### 3. Controller Updates

#### PetugasDashboardController (`app/Http/Controllers/PetugasDashboardController.php`)

**Perubahan:**

```php
// Menambahkan data peminjaman aktif petugas
$myActiveBorrowings = Borrowing::where('user_id', $user->id)
    ->where('status', 'borrowed')
    ->count();
```

#### AdminDashboardController (`app/Http/Controllers/AdminDashboardController.php`)

**Perubahan:**

```php
// Menambahkan data peminjaman aktif admin
$myActiveBorrowings = \App\Models\Borrowing::where('user_id', $user->id)
    ->where('status', 'borrowed')
    ->count();
```

### 4. Halaman Pengembalian

**View**: `resources/views/borrowings/return.blade.php` (sudah ada, tidak perlu diubah)

**Controller**: `BorrowingController::returnPage()` (sudah ada, tidak perlu diubah)

**Route**: `borrowings.return.page` (sudah ada di `routes/web.php`)

Halaman ini sudah otomatis bekerja untuk semua role karena:

- Menggunakan `Auth::user()` untuk mendapatkan user yang login
- Hanya menampilkan peminjaman milik user tersebut
- Logika return sudah mendukung role-based authorization

## Cara Kerja

### Flow untuk Petugas:

1. Petugas login dan melihat dashboard
2. Jika ada buku yang dipinjam, muncul banner hijau dengan jumlah buku
3. Klik "Kembalikan Buku" atau menu sidebar "Pengembalian Buku Saya"
4. Melihat daftar buku yang dipinjam dengan status (tepat waktu/terlambat)
5. Klik tombol "Kembalikan Buku" pada buku yang ingin dikembalikan
6. Konfirmasi pengembalian
7. Buku berhasil dikembalikan

### Flow untuk Admin:

1. Admin login dan melihat dashboard
2. Jika ada buku yang dipinjam, muncul banner merah dengan jumlah buku
3. Klik "Kembalikan Buku" atau menu sidebar "Pengembalian Buku Saya"
4. Melihat daftar buku yang dipinjam dengan status (tepat waktu/terlambat)
5. Klik tombol "Kembalikan Buku" pada buku yang ingin dikembalikan
6. Konfirmasi pengembalian
7. Buku berhasil dikembalikan

## Authorization Rules (Tetap Berlaku)

Sesuai dengan logika yang sudah dibuat sebelumnya:

1. **Admin meminjam buku** → Hanya admin yang bisa mengembalikan
2. **Petugas meminjam buku** → Hanya petugas yang bisa mengembalikan
3. **User meminjam buku** → User sendiri, admin, atau petugas bisa mengembalikan

## UI/UX Features

### Halaman Pengembalian:

- ✅ Info card dengan panduan pengembalian
- ✅ Empty state jika tidak ada buku dipinjam
- ✅ Card untuk setiap buku dengan:
    - Cover buku (atau placeholder)
    - Judul dan penulis
    - Tanggal pinjam dan jatuh tempo
    - Status badge (tepat waktu/terlambat)
    - Tombol "Kembalikan Buku"
    - Catatan peminjaman (jika ada)
- ✅ Warning banner untuk buku terlambat
- ✅ Summary card dengan total buku dipinjam
- ✅ Konfirmasi sebelum mengembalikan

### CTA Banner di Dashboard:

- ✅ Gradient background (hijau untuk petugas, merah untuk admin)
- ✅ Icon upload yang menarik
- ✅ Judul dan deskripsi yang jelas
- ✅ Highlight jumlah buku dengan warna bold
- ✅ Tombol CTA yang prominent
- ✅ Responsive design

## Files Modified

1. ✅ `resources/views/components/sidebar-petugas.blade.php`
    - Menambahkan menu "Pengembalian Buku Saya"

2. ✅ `resources/views/components/sidebar-admin.blade.php`
    - Menambahkan menu "Pengembalian Buku Saya"

3. ✅ `app/Http/Controllers/PetugasDashboardController.php`
    - Menambahkan `$myActiveBorrowings` ke data view

4. ✅ `app/Http/Controllers/AdminDashboardController.php`
    - Menambahkan `$myActiveBorrowings` ke data view

5. ✅ `resources/views/dashboards/petugas.blade.php`
    - Menambahkan CTA banner pengembalian buku

6. ✅ `resources/views/dashboards/admin.blade.php`
    - Menambahkan CTA banner pengembalian buku

## Testing Checklist

### Test sebagai Petugas:

- [ ] Login sebagai petugas
- [ ] Pinjam buku dari halaman "Kelola Buku"
- [ ] Cek dashboard, pastikan CTA banner muncul
- [ ] Klik menu sidebar "Pengembalian Buku Saya"
- [ ] Verifikasi buku yang dipinjam muncul
- [ ] Klik "Kembalikan Buku" dan konfirmasi
- [ ] Verifikasi buku berhasil dikembalikan
- [ ] Cek dashboard, pastikan CTA banner hilang

### Test sebagai Admin:

- [ ] Login sebagai admin
- [ ] Pinjam buku dari halaman "Kelola Buku"
- [ ] Cek dashboard, pastikan CTA banner muncul
- [ ] Klik menu sidebar "Pengembalian Buku Saya"
- [ ] Verifikasi buku yang dipinjam muncul
- [ ] Klik "Kembalikan Buku" dan konfirmasi
- [ ] Verifikasi buku berhasil dikembalikan
- [ ] Cek dashboard, pastikan CTA banner hilang

### Test Authorization:

- [ ] Admin pinjam buku → Petugas tidak bisa return (di halaman Kelola Peminjaman)
- [ ] Petugas pinjam buku → Admin tidak bisa return (di halaman Kelola Peminjaman)
- [ ] Admin/Petugas hanya bisa return buku mereka sendiri di halaman "Pengembalian Buku Saya"

## Benefits

1. **Konsistensi UX**: Semua role memiliki cara yang sama untuk mengelola peminjaman mereka
2. **Self-Service**: Admin dan petugas bisa mengembalikan buku mereka sendiri tanpa bantuan
3. **Visibility**: CTA banner di dashboard mengingatkan untuk mengembalikan buku
4. **Separation of Concerns**: Halaman terpisah untuk mengelola peminjaman sendiri vs mengelola peminjaman orang lain
5. **User-Friendly**: Interface yang intuitif dengan status dan warning yang jelas

## Notes

- Halaman "Kelola Peminjaman" tetap digunakan untuk mengelola peminjaman semua user
- Halaman "Pengembalian Buku Saya" khusus untuk mengelola peminjaman sendiri
- Authorization tetap diterapkan di backend untuk keamanan
- CTA banner hanya muncul jika ada buku yang dipinjam
