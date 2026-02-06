# Panduan Sistem Multi Role Authentication Laravel

## Fitur yang Sudah Dibuat

### 1. **3 Role User**

- **User** - Role default untuk user baru
- **Petugas** - Role untuk petugas dengan akses terbatas
- **Admin** - Role dengan akses penuh untuk mengelola sistem

### 2. **Fitur Utama**

- ✅ Register dengan pilihan role (User/Petugas)
- ✅ Login dengan redirect otomatis berdasarkan role
- ✅ Dashboard berbeda untuk setiap role
- ✅ User bisa request upgrade role menjadi Petugas
- ✅ Admin bisa approve/reject request role
- ✅ Admin bisa mengubah role user secara langsung
- ✅ Middleware untuk proteksi route berdasarkan role

## Struktur Database

### Tabel Users

- `id` - Primary key
- `name` - Nama user
- `email` - Email (unique)
- `password` - Password (hashed)
- `role` - Enum: user, petugas, admin (default: user)
- `email_verified_at` - Timestamp verifikasi email
- `created_at`, `updated_at` - Timestamps

### Tabel Role Requests

- `id` - Primary key
- `user_id` - Foreign key ke users
- `requested_role` - Role yang diminta (petugas)
- `reason` - Alasan permintaan
- `status` - Enum: pending, approved, rejected (default: pending)
- `approved_by` - Foreign key ke users (admin yang approve)
- `approved_at` - Timestamp approval
- `created_at`, `updated_at` - Timestamps

## Akun Default (Seeder)

Setelah menjalankan `php artisan db:seed`, Anda akan memiliki 3 akun:

### Admin

- **Email**: admin@gmail.com
- **Password**: password
- **Role**: admin

### Petugas

- **Email**: petugas@gmail.com
- **Password**: password
- **Role**: petugas

### User

- **Email**: user@gmail.com
- **Password**: password
- **Role**: user

## Routes yang Tersedia

### Public Routes

- `GET /` - Landing page
- `GET /login` - Halaman login
- `GET /register` - Halaman register
- `POST /register` - Proses register

### User Routes (Role: user)

- `GET /user/dashboard` - Dashboard user
- `POST /user/request-role` - Request upgrade role

### Petugas Routes (Role: petugas)

- `GET /petugas/dashboard` - Dashboard petugas

### Admin Routes (Role: admin)

- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/users` - Kelola semua users
- `PATCH /admin/users/{user}/role` - Update role user
- `GET /admin/role-requests` - Lihat semua request role
- `PATCH /admin/role-requests/{roleRequest}` - Approve/reject request

## Cara Menggunakan

### 1. Setup Database

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder untuk membuat akun default
php artisan db:seed
```

### 2. Jalankan Server

```bash
php artisan serve
```

### 3. Testing Flow

#### A. Login sebagai User

1. Buka http://127.0.0.1:8000/login
2. Login dengan: user@gmail.com / password
3. Anda akan diarahkan ke `/user/dashboard`
4. Di dashboard, Anda bisa request role menjadi Petugas
5. Isi form request dengan alasan yang jelas
6. Status request akan muncul di tabel riwayat

#### B. Login sebagai Admin

1. Logout dari akun user
2. Login dengan: admin@gmail.com / password
3. Anda akan diarahkan ke `/admin/dashboard`
4. Klik "Lihat Permintaan" untuk melihat request role
5. Approve atau reject request dari user
6. Atau klik "Kelola Users" untuk mengubah role user secara langsung

#### C. Login sebagai Petugas

1. Login dengan: petugas@gmail.com / password
2. Anda akan diarahkan ke `/petugas/dashboard`
3. Dashboard petugas memiliki fitur khusus untuk petugas

### 4. Register User Baru

1. Buka http://127.0.0.1:8000/register
2. Isi form registrasi
3. Pilih role: User atau Petugas
4. Jika pilih Petugas, akun akan terdaftar sebagai User dulu
5. Setelah register, login dan request role upgrade

## Middleware Usage

Untuk melindungi route dengan role tertentu:

```php
// Single role
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Routes untuk admin saja
});

// Multiple roles
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    // Routes untuk admin dan petugas
});
```

## Model Methods

### User Model

```php
// Check role
$user->isAdmin();    // return boolean
$user->isPetugas();  // return boolean
$user->isUser();     // return boolean

// Relationships
$user->roleRequests;          // Get all role requests
$user->approvedRoleRequests;  // Get requests yang di-approve oleh user ini
```

### RoleRequest Model

```php
// Scopes
RoleRequest::pending()->get();   // Get pending requests
RoleRequest::approved()->get();  // Get approved requests
RoleRequest::rejected()->get();  // Get rejected requests

// Relationships
$request->user;      // User yang request
$request->approver;  // Admin yang approve/reject
```

## File-file Penting

### Controllers

- `app/Http/Controllers/UserDashboardController.php` - Dashboard user
- `app/Http/Controllers/PetugasDashboardController.php` - Dashboard petugas
- `app/Http/Controllers/AdminDashboardController.php` - Dashboard admin & management
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Register dengan role
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Login redirect

### Models

- `app/Models/User.php` - User model dengan role methods
- `app/Models/RoleRequest.php` - Role request model

### Middleware

- `app/Http/Middleware/RoleMiddleware.php` - Middleware untuk check role

### Views

- `resources/views/dashboards/user.blade.php` - Dashboard user
- `resources/views/dashboards/petugas.blade.php` - Dashboard petugas
- `resources/views/dashboards/admin.blade.php` - Dashboard admin
- `resources/views/admin/users.blade.php` - Kelola users
- `resources/views/admin/role-requests.blade.php` - Kelola role requests
- `resources/views/auth/register.blade.php` - Form register dengan role selection

### Migrations

- `database/migrations/2026_02_05_023027_add_role_to_users_table.php` - Tambah kolom role
- `database/migrations/2026_02_05_023108_create_role_requests_table.php` - Tabel role requests

## Customization

### Menambah Role Baru

1. Update migration `add_role_to_users_table.php`:

```php
$table->enum('role', ['user', 'petugas', 'admin', 'role_baru'])->default('user');
```

2. Tambah method di User model:

```php
public function isRoleBaru()
{
    return $this->role === 'role_baru';
}
```

3. Update middleware dan routes sesuai kebutuhan

### Menambah Fitur di Dashboard

Edit file view dashboard yang sesuai:

- User: `resources/views/dashboards/user.blade.php`
- Petugas: `resources/views/dashboards/petugas.blade.php`
- Admin: `resources/views/dashboards/admin.blade.php`

## Troubleshooting

### Error: Class 'RoleRequest' not found

Pastikan sudah import di User model:

```php
use App\Models\RoleRequest;
```

### Error: Route not found

Jalankan:

```bash
php artisan route:clear
php artisan route:cache
```

### Error: Middleware not found

Pastikan middleware sudah terdaftar di `bootstrap/app.php`

## Security Notes

1. ✅ Password di-hash menggunakan bcrypt
2. ✅ Middleware melindungi route berdasarkan role
3. ✅ Admin tidak bisa mengubah role dirinya sendiri di UI
4. ✅ CSRF protection aktif di semua form
5. ✅ Email verification tersedia (optional)

## Next Steps

Anda bisa mengembangkan lebih lanjut dengan:

- Menambah fitur notifikasi untuk request role
- Menambah log aktivitas admin
- Menambah fitur export data
- Menambah dashboard analytics
- Menambah permission granular per fitur

---

**Dibuat dengan Laravel 11**
**Tanggal: 5 Februari 2026**
