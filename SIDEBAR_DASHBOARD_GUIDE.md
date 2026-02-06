# Panduan Dashboard dengan Sidebar & Logout

## Fitur yang Ditambahkan

### ✅ **Layout Dashboard Baru dengan Sidebar**

- Sidebar responsif dengan menu navigasi yang rapi
- Header dengan informasi user dan tombol logout
- Mobile-friendly dengan hamburger menu
- Smooth animations dan modern design

### ✅ **Logout Functionality**

- Tombol logout di sidebar (bawah)
- Tombol logout di profile dropdown (header)
- Form logout dengan CSRF protection
- Redirect ke landing page setelah logout

### ✅ **Sidebar Menu per Role**

#### **User Dashboard**

- Dashboard (aktif)
- Profile
- Request Role (jika role = user)
- Riwayat Permintaan
- Bantuan

#### **Petugas Dashboard**

- Dashboard (aktif)
- **Data Management:**
    - Kelola Buku
    - Kelola Anggota
    - Kategori Buku
- **Transaksi:**
    - Peminjaman
    - Pengembalian
- **Laporan:**
    - Laporan Peminjaman
    - Statistik Buku
- Profile

#### **Admin Dashboard**

- Dashboard (aktif)
- **User Management:**
    - Kelola Users
    - Permintaan Role (dengan badge notifikasi)
- **System Management:**
    - Kelola Buku
    - Kategori & Tag
    - Pengaturan Sistem
- **Reports & Analytics:**
    - Laporan Lengkap
    - Analytics Dashboard
    - Log Aktivitas
- Profile

## Struktur File

### **Layout Dashboard (`resources/views/layouts/dashboard.blade.php`)**

- Layout utama dengan sidebar dan header
- User info section dengan avatar dan role
- Navigation menu (yield dari section)
- Logout button di sidebar
- Mobile responsive dengan overlay
- Alpine.js untuk dropdown interactions

### **Dashboard Views**

- `resources/views/dashboards/user.blade.php` - Dashboard user dengan sidebar
- `resources/views/dashboards/petugas.blade.php` - Dashboard petugas dengan sidebar
- `resources/views/dashboards/admin.blade.php` - Dashboard admin dengan sidebar

### **Admin Management Views**

- `resources/views/admin/users.blade.php` - Kelola users dengan sidebar
- `resources/views/admin/role-requests.blade.php` - Kelola role requests dengan sidebar

## Fitur Sidebar

### **User Info Section**

- Avatar dengan inisial nama (gradient background)
- Nama user dan role
- Responsive design

### **Navigation Menu**

- Grouped menu dengan section headers
- Active state highlighting
- Icon untuk setiap menu item
- Badge notifications (untuk admin role requests)
- Hover effects dan smooth transitions

### **Logout Section**

- Fixed position di bottom sidebar
- Icon dan text "Logout"
- Hover effects dengan red color scheme

### **Mobile Features**

- Hamburger menu button di header
- Sidebar slide animation
- Overlay background
- Touch-friendly interactions

## Header Features

### **Mobile Menu Button**

- Hamburger icon untuk mobile
- Toggle sidebar visibility
- Hidden di desktop (lg:hidden)

### **Page Title**

- Dynamic title berdasarkan halaman
- Yield dari section 'page-title'

### **Right Side Actions**

- Notification bell (dengan badge untuk admin)
- Profile dropdown dengan Alpine.js
- Avatar dan logout option

## CSS & Styling

### **Color Scheme per Role**

- **User**: Purple gradient (purple-500 to blue-500)
- **Petugas**: Green gradient (green-500 to teal-500)
- **Admin**: Red gradient (red-500 to pink-500)

### **Sidebar Styling**

- White background dengan shadow
- Rounded corners untuk menu items
- Gradient header dengan logo
- Smooth hover transitions
- Active state dengan background color

### **Responsive Breakpoints**

- Mobile: Sidebar hidden, overlay menu
- Desktop (lg+): Sidebar always visible
- Smooth transitions between states

## JavaScript Features

### **Mobile Sidebar Toggle**

```javascript
// Toggle sidebar visibility
sidebar.classList.toggle("-translate-x-full");
overlay.classList.toggle("hidden");
```

### **Alpine.js Dropdown**

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Profile</button>
    <div x-show="open" @click.away="open = false">
        <!-- Dropdown content -->
    </div>
</div>
```

## Cara Menggunakan

### **1. Akses Dashboard**

- Login dengan role apapun
- Otomatis redirect ke dashboard sesuai role
- Sidebar muncul dengan menu sesuai role

### **2. Navigasi**

- Klik menu di sidebar untuk navigasi
- Active state menunjukkan halaman saat ini
- Smooth scroll untuk anchor links

### **3. Logout**

- Klik tombol "Logout" di sidebar, atau
- Klik avatar → dropdown → "Logout"
- Otomatis redirect ke landing page

### **4. Mobile Usage**

- Klik hamburger menu untuk buka sidebar
- Klik overlay atau menu item untuk tutup
- Touch-friendly interactions

## Customization

### **Menambah Menu Baru**

Edit section `@section('sidebar-menu')` di dashboard view:

```html
<a
    href="{{ route('new.route') }}"
    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-lg mb-2"
>
    <svg
        class="w-5 h-5 mr-3"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
    >
        <!-- Icon SVG -->
    </svg>
    Menu Baru
</a>
```

### **Mengubah Warna Role**

Edit gradient di user info section:

```html
<!-- User: purple-500 to blue-500 -->
<!-- Petugas: green-500 to teal-500 -->
<!-- Admin: red-500 to pink-500 -->
<div
    class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full"
></div>
```

### **Menambah Badge Notification**

```html
<a href="#" class="flex items-center px-4 py-2 ...">
    <svg>...</svg>
    Menu Item
    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full"
        >5</span
    >
</a>
```

### **Mengubah Layout**

Edit `resources/views/layouts/dashboard.blade.php`:

- Sidebar width: `w-64`
- Header height: `h-16`
- Content padding: `p-6`

## Security Features

### ✅ **CSRF Protection**

- Semua form logout menggunakan `@csrf`
- Laravel otomatis validasi token

### ✅ **Route Protection**

- Middleware `auth` untuk semua dashboard
- Middleware `role` untuk proteksi berdasarkan role
- Redirect otomatis jika tidak authorized

### ✅ **XSS Prevention**

- Blade escaping otomatis: `{{ $variable }}`
- Safe HTML rendering untuk trusted content

## Performance

### **Optimizations**

- Alpine.js CDN untuk dropdown (lightweight)
- CSS transitions untuk smooth animations
- Minimal JavaScript untuk mobile toggle
- Efficient DOM queries

### **Loading**

- Fast sidebar rendering
- Cached user data
- Optimized SVG icons
- Responsive images untuk avatar

## Browser Support

### **Modern Browsers**

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### **Features Used**

- CSS Grid & Flexbox
- CSS Transitions
- Alpine.js (modern JS)
- SVG icons

---

**Dashboard sekarang memiliki sidebar yang rapi dan professional dengan logout functionality yang aman!** 🎉

Setiap role memiliki menu yang sesuai dengan kebutuhan dan permission mereka, memberikan user experience yang optimal dan organized.
