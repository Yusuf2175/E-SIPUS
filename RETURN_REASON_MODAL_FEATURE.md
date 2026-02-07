# Fitur Modal Alasan Pengembalian Buku

## Overview

Menambahkan fitur modal dengan pilihan alasan pengembalian ketika admin atau petugas mengembalikan buku yang dipinjam oleh user biasa. Fitur ini membantu tracking kondisi buku dan alasan pengembalian.

## Business Rules

### Kapan Modal Muncul?

Modal **HANYA** muncul ketika:

- ✅ Admin mengembalikan buku yang dipinjam oleh **User Biasa**
- ✅ Petugas mengembalikan buku yang dipinjam oleh **User Biasa**

### Kapan Modal TIDAK Muncul?

Modal **TIDAK** muncul ketika:

- ❌ User mengembalikan bukunya sendiri (langsung submit)
- ❌ Admin mengembalikan bukunya sendiri (langsung submit)
- ❌ Petugas mengembalikan bukunya sendiri (langsung submit)
- ❌ Admin mengembalikan buku Admin lain (tidak bisa)
- ❌ Petugas mengembalikan buku Petugas lain (tidak bisa)

## Pilihan Alasan Pengembalian

### 1. Normal (Hijau)

- **Value**: `normal`
- **Deskripsi**: Buku dikembalikan dalam kondisi baik
- **Aksi**: Stok buku bertambah (+1)
- **Icon**: ✅

### 2. User Menghilang (Kuning)

- **Value**: `user_missing`
- **Deskripsi**: User tidak dapat dihubungi
- **Aksi**: Stok buku bertambah (+1)
- **Icon**: ⚠️

### 3. Buku Rusak (Orange)

- **Value**: `book_damaged`
- **Deskripsi**: Buku dikembalikan dalam kondisi rusak
- **Aksi**: Stok buku bertambah (+1)
- **Icon**: 🔧

### 4. Buku Hilang (Merah)

- **Value**: `book_lost`
- **Deskripsi**: Buku tidak dapat dikembalikan
- **Aksi**: Stok buku **TIDAK** bertambah (buku hilang)
- **Icon**: ❌

## Database Changes

### Migration: `add_return_reason_to_borrowings_table`

**Kolom Baru:**

```php
$table->string('return_reason')->nullable();      // Alasan pengembalian
$table->text('return_notes')->nullable();         // Catatan tambahan
$table->unsignedBigInteger('returned_by')->nullable(); // Siapa yang mengembalikan
$table->foreign('returned_by')->references('id')->on('users');
```

**Nilai `return_reason`:**

- `normal` - Pengembalian normal
- `user_missing` - User menghilang
- `book_damaged` - Buku rusak
- `book_lost` - Buku hilang

## Implementation Details

### 1. Model Update (`app/Models/Borrowing.php`)

**Fillable Fields:**

```php
protected $fillable = [
    // ... existing fields
    'return_reason',
    'return_notes',
    'returned_by'
];
```

**New Relationship:**

```php
public function returnedBy(): BelongsTo
{
    return $this->belongsTo(User::class, 'returned_by');
}
```

### 2. Controller Update (`app/Http/Controllers/BorrowingController.php`)

**Method: `return(Request $request, Borrowing $borrowing)`**

**Validation:**

```php
// Validate return reason if returned by admin/petugas for user's book
if (($currentUser->isAdmin() || $currentUser->isPetugas()) && $borrower->isUser()) {
    $request->validate([
        'return_reason' => 'required|in:normal,user_missing,book_damaged,book_lost',
        'return_notes' => 'nullable|string|max:500'
    ]);
}
```

**Logic:**

```php
// Update borrowing with return reason
$updateData = [
    'status' => 'returned',
    'returned_date' => Carbon::now()->toDateString(),
    'returned_by' => $currentUser->id
];

if ($request->filled('return_reason')) {
    $updateData['return_reason'] = $request->return_reason;
    $updateData['return_notes'] = $request->return_notes;
}

$borrowing->update($updateData);

// Increase available copies (except if book is lost)
if ($request->return_reason !== 'book_lost') {
    $borrowing->book->increment('available_copies');
}
```

### 3. View Update (`resources/views/borrowings/index.blade.php`)

**Conditional Button:**

```blade
@if(($currentUser->isAdmin() || $currentUser->isPetugas()) && $borrower->isUser())
    {{-- Show modal button --}}
    <button type="button" onclick="openReturnModal({{ $borrowing->id }})">
        Kembalikan
    </button>
@else
    {{-- Direct submit form --}}
    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit">Kembalikan</button>
    </form>
@endif
```

**Modal Structure:**

- Header dengan gradient blue-purple
- 4 pilihan radio button dengan hover effect
- Textarea untuk catatan tambahan (opsional)
- Footer dengan tombol Batal dan Kembalikan

**JavaScript Functions:**

```javascript
openReturnModal(borrowingId); // Buka modal dan set form action
closeReturnModal(); // Tutup modal dan reset form
```

## UI/UX Features

### Modal Design:

- ✅ Gradient header (blue to purple)
- ✅ Radio buttons dengan hover effect berbeda per opsi
- ✅ Icon dan deskripsi untuk setiap pilihan
- ✅ Textarea untuk catatan tambahan
- ✅ Responsive design
- ✅ Backdrop blur effect
- ✅ Smooth transitions

### User Experience:

- ✅ Modal dapat ditutup dengan:
    - Klik tombol "Batal"
    - Klik di luar modal
    - Tekan tombol Escape
- ✅ Form validation (alasan wajib dipilih)
- ✅ Auto-focus pada modal saat dibuka
- ✅ Body scroll disabled saat modal terbuka

### Color Coding:

- 🟢 **Normal**: Green (border-green-500, bg-green-50)
- 🟡 **User Menghilang**: Yellow (border-yellow-500, bg-yellow-50)
- 🟠 **Buku Rusak**: Orange (border-orange-500, bg-orange-50)
- 🔴 **Buku Hilang**: Red (border-red-500, bg-red-50)

## Testing Scenarios

### Scenario 1: Admin Return User's Book

1. Login sebagai **Admin**
2. Buka halaman **Kolola Peminjaman**
3. Cari buku yang dipinjam oleh **User Biasa** (badge biru)
4. Klik tombol **"Kembalikan"**
5. ✅ Modal muncul dengan 4 pilihan
6. Pilih salah satu alasan (misal: "Normal")
7. Tambahkan catatan (opsional)
8. Klik **"Kembalikan Buku"**
9. ✅ Buku berhasil dikembalikan
10. ✅ Stok buku bertambah (kecuali jika "Buku Hilang")

### Scenario 2: Petugas Return User's Book

1. Login sebagai **Petugas**
2. Buka halaman **Kolola Peminjaman**
3. Cari buku yang dipinjam oleh **User Biasa** (badge biru)
4. Klik tombol **"Kembalikan"**
5. ✅ Modal muncul dengan 4 pilihan
6. Pilih "Buku Hilang"
7. Tambahkan catatan: "Buku tidak dikembalikan oleh user"
8. Klik **"Kembalikan Buku"**
9. ✅ Buku berhasil dikembalikan
10. ✅ Stok buku **TIDAK** bertambah

### Scenario 3: User Return Own Book

1. Login sebagai **User**
2. Buka halaman **Pengembalian Buku Saya**
3. Klik tombol **"Kembalikan Buku"**
4. ✅ Konfirmasi langsung (tanpa modal)
5. ✅ Buku berhasil dikembalikan

### Scenario 4: Admin Return Own Book

1. Login sebagai **Admin**
2. Buka halaman **Pengembalian Buku Saya**
3. Klik tombol **"Kembalikan Buku"**
4. ✅ Konfirmasi langsung (tanpa modal)
5. ✅ Buku berhasil dikembalikan

### Scenario 5: Book Lost - Stock Not Increased

1. Admin/Petugas return user's book
2. Pilih alasan **"Buku Hilang"**
3. Submit form
4. ✅ Status borrowing = 'returned'
5. ✅ `return_reason` = 'book_lost'
6. ✅ Stok buku **TIDAK** bertambah
7. ✅ `returned_by` = ID admin/petugas

## Database Queries

### Check Return Reason:

```sql
SELECT
    b.id,
    u.name as borrower,
    bk.title as book,
    b.return_reason,
    b.return_notes,
    rb.name as returned_by
FROM borrowings b
JOIN users u ON b.user_id = u.id
JOIN books bk ON b.book_id = bk.id
LEFT JOIN users rb ON b.returned_by = rb.id
WHERE b.status = 'returned'
AND b.return_reason IS NOT NULL;
```

### Count by Return Reason:

```sql
SELECT
    return_reason,
    COUNT(*) as total
FROM borrowings
WHERE status = 'returned'
AND return_reason IS NOT NULL
GROUP BY return_reason;
```

### Lost Books Report:

```sql
SELECT
    bk.title,
    u.name as borrower,
    b.borrowed_date,
    b.returned_date,
    b.return_notes,
    rb.name as returned_by
FROM borrowings b
JOIN books bk ON b.book_id = bk.id
JOIN users u ON b.user_id = u.id
JOIN users rb ON b.returned_by = rb.id
WHERE b.return_reason = 'book_lost'
ORDER BY b.returned_date DESC;
```

## Benefits

1. **Tracking**: Dapat melacak alasan pengembalian buku
2. **Accountability**: Tahu siapa yang mengembalikan buku (returned_by)
3. **Inventory Management**: Buku hilang tidak menambah stok
4. **Reporting**: Data untuk laporan buku rusak/hilang
5. **User Behavior**: Tracking user yang sering menghilang
6. **Book Condition**: Monitoring kondisi buku perpustakaan

## Future Enhancements

1. **Denda Otomatis**:
    - Buku rusak → denda Rp 50.000
    - Buku hilang → denda harga buku

2. **Notifikasi**:
    - Email ke user jika buku dikembalikan dengan alasan khusus
    - Notifikasi ke admin jika banyak buku hilang

3. **Blacklist**:
    - User dengan buku hilang > 2x tidak bisa pinjam lagi

4. **Report Dashboard**:
    - Chart kondisi pengembalian buku
    - Top users dengan masalah pengembalian

5. **Photo Upload**:
    - Upload foto buku rusak sebagai bukti

## Files Modified

1. ✅ `database/migrations/2026_02_07_051833_add_return_reason_to_borrowings_table.php`
2. ✅ `app/Models/Borrowing.php`
3. ✅ `app/Http/Controllers/BorrowingController.php`
4. ✅ `resources/views/borrowings/index.blade.php`

## Conclusion

Fitur ini memberikan kontrol lebih baik dalam pengelolaan pengembalian buku, terutama untuk kasus-kasus khusus seperti user menghilang atau buku rusak/hilang. Modal yang user-friendly memudahkan admin dan petugas untuk mendokumentasikan kondisi pengembalian dengan baik.
