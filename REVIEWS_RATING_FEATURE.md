# Fitur Ulasan & Rating Buku

## Overview

Menambahkan halaman untuk melihat dan mengelola ulasan/rating buku dengan aturan akses berbeda untuk setiap role:

- **Admin & Petugas**: Dapat melihat SEMUA ulasan dan menghapus ulasan
- **User**: Hanya dapat melihat ulasan mereka sendiri

## Business Rules

### Akses Halaman:

1. **Admin**:
    - ✅ Melihat semua ulasan dari semua user
    - ✅ Statistik lengkap (total ulasan, rata-rata rating, distribusi)
    - ✅ Filter berdasarkan rating, buku, dan pencarian
    - ✅ Menghapus ulasan apapun

2. **Petugas**:
    - ✅ Melihat semua ulasan dari semua user
    - ✅ Statistik lengkap (total ulasan, rata-rata rating, distribusi)
    - ✅ Filter berdasarkan rating, buku, dan pencarian
    - ✅ Menghapus ulasan apapun

3. **User**:
    - ✅ Hanya melihat ulasan mereka sendiri
    - ❌ Tidak ada statistik
    - ✅ Filter berdasarkan rating dan pencarian
    - ❌ Tidak bisa menghapus ulasan (hanya di halaman detail buku)

## Features

### 1. Statistics Dashboard (Admin & Petugas Only)

**Cards:**

- **Total Ulasan**: Jumlah total semua ulasan
- **Rating Rata-rata**: Average rating dari semua ulasan
- **Ulasan Bintang 5**: Jumlah ulasan dengan rating 5
- **Rating Rendah**: Jumlah ulasan dengan rating ≤2

**Distribusi Rating:**

- Bar chart horizontal untuk setiap rating (1-5 bintang)
- Menampilkan jumlah dan persentase
- Visual progress bar dengan warna kuning

### 2. Filter & Search

**Filter Options:**

- **Search**: Cari berdasarkan:
    - Isi ulasan
    - Nama user
    - Judul buku
- **Rating**: Filter berdasarkan rating (1-5 bintang)
- **Buku**: Filter berdasarkan buku tertentu (Admin & Petugas only)

**Reset Button**: Menghapus semua filter

### 3. Review List

**Setiap Review Card Menampilkan:**

- Cover buku (atau placeholder gradient)
- Judul dan penulis buku
- Rating bintang (visual stars)
- Isi ulasan
- Nama reviewer dengan avatar
- Waktu posting (relative time)
- Tombol hapus (Admin & Petugas only)

### 4. Pagination

- 15 ulasan per halaman
- Laravel pagination links

## Implementation Details

### Controller: `ReviewController.php`

**New Method: `index()`**

```php
public function index(Request $request)
{
    $user = Auth::user();
    $query = Review::with(['book', 'user']);

    // User can only see their own reviews
    if ($user->isUser()) {
        $query->where('user_id', $user->id);
    }

    // Filters: rating, book_id, search
    // Statistics for Admin/Petugas
    // Pagination: 15 per page
}
```

**New Method: `adminDestroy()`**

```php
public function adminDestroy(Review $review)
{
    // Only admin and petugas can delete any review
    if (!$user->isAdmin() && !$user->isPetugas()) {
        abort(403);
    }

    $review->delete();
}
```

### Routes: `routes/web.php`

**New Routes:**

```php
// Authenticated users
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Admin & Petugas only
Route::delete('/reviews/{review}/admin', [ReviewController::class, 'adminDestroy'])
    ->name('reviews.admin.destroy');
```

### View: `resources/views/reviews/index.blade.php`

**Structure:**

1. Sidebar (conditional based on role)
2. Top bar with user info
3. Statistics cards (Admin & Petugas only)
4. Rating distribution chart (Admin & Petugas only)
5. Filter form
6. Reviews list
7. Pagination

**Responsive Design:**

- Grid layout for statistics cards
- Responsive filter form
- Mobile-friendly review cards

## UI/UX Features

### Color Scheme:

- **Admin**: Red theme (bg-red-50, text-red-700)
- **Petugas**: Green theme (bg-green-50, text-green-700)
- **User**: Purple theme (bg-purple-50, text-purple-700)

### Visual Elements:

- ✅ Gradient book cover placeholders
- ✅ Star rating visualization (yellow stars)
- ✅ User avatars with initials
- ✅ Hover effects on cards
- ✅ Smooth transitions
- ✅ Empty state with icon and message

### Statistics Visualization:

- **Total Reviews**: Blue card with chat icon
- **Average Rating**: Yellow card with star icon
- **5 Star Reviews**: Green card with "5★"
- **Low Ratings**: Red card with "≤2★"

### Rating Distribution:

- Horizontal bar chart
- Yellow progress bars
- Shows count and percentage
- Sorted from 5 to 1 stars

## Sidebar Menu

### Admin Sidebar:

```
Perpustakaan
├── Kelola Buku
├── Kelola Peminjaman
├── Pengembalian Buku Saya
└── Ulasan & Rating  ← NEW
```

### Petugas Sidebar:

```
Manajemen
├── Kelola Buku
├── Kelola Peminjaman
├── Pengembalian Buku Saya
└── Ulasan & Rating  ← NEW
```

### User Sidebar:

```
Aktivitas Saya
├── Peminjaman Saya
├── Pengembalian Buku
├── Koleksi Pribadi
└── Ulasan Saya  ← NEW
```

## Testing Scenarios

### Test sebagai Admin:

1. Login sebagai **Admin**
2. Klik menu **"Ulasan & Rating"** di sidebar
3. ✅ Melihat statistics cards
4. ✅ Melihat rating distribution chart
5. ✅ Melihat SEMUA ulasan dari semua user
6. ✅ Filter berdasarkan rating (pilih "5 Bintang")
7. ✅ Filter berdasarkan buku
8. ✅ Search ulasan
9. ✅ Klik tombol "Hapus" pada ulasan
10. ✅ Ulasan berhasil dihapus

### Test sebagai Petugas:

1. Login sebagai **Petugas**
2. Klik menu **"Ulasan & Rating"** di sidebar
3. ✅ Melihat statistics cards
4. ✅ Melihat rating distribution chart
5. ✅ Melihat SEMUA ulasan dari semua user
6. ✅ Filter dan search berfungsi
7. ✅ Dapat menghapus ulasan

### Test sebagai User:

1. Login sebagai **User**
2. Klik menu **"Ulasan Saya"** di sidebar
3. ❌ TIDAK melihat statistics cards
4. ❌ TIDAK melihat rating distribution
5. ✅ Hanya melihat ulasan sendiri
6. ✅ Filter rating berfungsi
7. ✅ Search berfungsi
8. ❌ TIDAK ada tombol hapus

### Test Filter & Search:

1. **Filter Rating**: Pilih "5 Bintang" → Hanya ulasan 5 bintang muncul
2. **Filter Buku**: Pilih buku tertentu → Hanya ulasan buku itu muncul
3. **Search**: Ketik "bagus" → Ulasan dengan kata "bagus" muncul
4. **Reset**: Klik "Reset" → Semua filter dihapus

### Test Empty State:

1. User baru yang belum pernah review
2. ✅ Melihat empty state dengan icon dan pesan
3. Pesan: "Anda belum memberikan ulasan untuk buku apapun"

## Database Queries

### Get All Reviews with Filters:

```php
$reviews = Review::with(['book', 'user'])
    ->when($user->isUser(), fn($q) => $q->where('user_id', $user->id))
    ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
    ->when($request->book_id, fn($q) => $q->where('book_id', $request->book_id))
    ->when($request->search, function($q) use ($search) {
        $q->where('review', 'like', "%{$search}%")
          ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
          ->orWhereHas('book', fn($q) => $q->where('title', 'like', "%{$search}%"));
    })
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

### Statistics:

```php
$stats = [
    'total_reviews' => Review::count(),
    'average_rating' => round(Review::avg('rating'), 1),
    'five_star' => Review::where('rating', 5)->count(),
    'four_star' => Review::where('rating', 4)->count(),
    'three_star' => Review::where('rating', 3)->count(),
    'two_star' => Review::where('rating', 2)->count(),
    'one_star' => Review::where('rating', 1)->count(),
];
```

## Benefits

1. **Monitoring**: Admin/Petugas dapat memonitor semua ulasan
2. **Quality Control**: Dapat menghapus ulasan yang tidak pantas
3. **Insights**: Statistik rating membantu evaluasi koleksi buku
4. **User Privacy**: User hanya melihat ulasan mereka sendiri
5. **Easy Management**: Filter dan search memudahkan pencarian
6. **Data Visualization**: Chart distribusi rating mudah dipahami

## Future Enhancements

1. **Moderasi Otomatis**:
    - Deteksi kata-kata kasar
    - Auto-flag ulasan mencurigakan

2. **Response System**:
    - Admin/Petugas bisa reply ulasan
    - User dapat edit ulasan mereka

3. **Advanced Analytics**:
    - Trend rating per bulan
    - Top reviewed books
    - Most active reviewers

4. **Export Report**:
    - Export ulasan ke CSV/PDF
    - Report bulanan rating buku

5. **Notification**:
    - Notif ke admin jika ada rating rendah
    - Email ke user jika ulasan mereka dihapus

6. **Helpful Votes**:
    - User lain bisa vote "helpful" pada ulasan
    - Sort by most helpful

## Files Modified/Created

1. ✅ `app/Http/Controllers/ReviewController.php`
    - Added `index()` method
    - Added `adminDestroy()` method

2. ✅ `routes/web.php`
    - Added `reviews.index` route
    - Added `reviews.admin.destroy` route

3. ✅ `resources/views/reviews/index.blade.php` (NEW)
    - Complete review management page

4. ✅ `resources/views/components/sidebar-admin.blade.php`
    - Added "Ulasan & Rating" menu

5. ✅ `resources/views/components/sidebar-petugas.blade.php`
    - Added "Ulasan & Rating" menu

6. ✅ `resources/views/components/sidebar-user.blade.php`
    - Added "Ulasan Saya" menu

## Security Considerations

1. **Authorization**:
    - User hanya bisa lihat ulasan sendiri
    - Admin/Petugas bisa lihat semua

2. **Delete Permission**:
    - User tidak bisa hapus via halaman ini
    - Hanya Admin/Petugas yang bisa hapus

3. **Input Validation**:
    - Filter rating: 1-5 only
    - Search: sanitized input

4. **SQL Injection Prevention**:
    - Using Eloquent ORM
    - Parameterized queries

## Conclusion

Fitur ini memberikan kontrol penuh kepada admin dan petugas untuk memonitor dan mengelola ulasan buku, sambil tetap menjaga privasi user dengan hanya menampilkan ulasan mereka sendiri. Statistik dan visualisasi membantu dalam evaluasi kualitas koleksi perpustakaan.
