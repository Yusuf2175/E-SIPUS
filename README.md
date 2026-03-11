# E-Sipus - Sistem Informasi Perpustakaan

Aplikasi manajemen perpustakaan berbasis web menggunakan Laravel 12, Tailwind CSS, dan DaisyUI.

## 📋 Persyaratan Sistem

Sebelum memulai instalasi, pastikan sistem Anda memiliki:

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM atau Yarn
- MySQL/PostgreSQL/SQLite
- Git

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd <project-folder>
```

### 2. Install Dependencies PHP

```bash
composer install
```

Package PHP yang akan terinstall otomatis:

- **Laravel Framework** v12
- **Laravel Breeze** - Authentication
- **Laravel DomPDF** - Generate PDF (langsung siap pakai, tidak perlu build)

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Install Dependencies Node.js

Install Tailwind CSS, DaisyUI, dan dependencies lainnya:

```bash
npm install
```

Package yang akan terinstall:

- **Tailwind CSS** v3.4.19 - Framework CSS utility-first
- **DaisyUI** v5.5.18 - Component library untuk Tailwind CSS
- **@tailwindcss/forms** - Plugin untuk styling form
- **Alpine.js** - Framework JavaScript ringan
- **Vite** - Build tool modern

**Catatan:** Package ini perlu di-build/compile (lihat langkah 8)

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

Jika ingin mengisi data awal (seeder):

```bash
php artisan db:seed
```

### 7. Buat Storage Link

```bash
php artisan storage:link
```

### 8. Build Assets

Untuk development:

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

## 🎨 Teknologi yang Digunakan

### Backend

- **Laravel 12** - PHP Framework
- **Laravel Breeze** - Authentication scaffolding
- **Laravel DomPDF** (barryvdh/laravel-dompdf) - Generate PDF

### Frontend

- **Tailwind CSS 3.4** - Utility-first CSS framework
- **DaisyUI 5.5** - Tailwind CSS component library
- **Alpine.js 3.4** - Lightweight JavaScript framework
- **SweetAlert2** - Beautiful alert/modal library
- **Vite** - Frontend build tool

### Development Tools

- **Laravel Pint** - Code style fixer
- **Laravel Sail** - Docker development environment
- **Pest PHP** - Testing framework

## 🔧 Konfigurasi Tambahan

### Tailwind CSS & DaisyUI

Konfigurasi Tailwind sudah tersedia di `tailwind.config.js`. DaisyUI sudah terintegrasi sebagai plugin Tailwind.

Untuk menggunakan tema DaisyUI, edit file `tailwind.config.js`:

```javascript
module.exports = {
    plugins: [require("daisyui")],
    daisyui: {
        themes: ["light", "dark", "cupcake"],
    },
};
```

### Laravel DomPDF

Package untuk generate PDF sudah terinstall. Untuk menggunakannya:

```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('pdf.document', $data);
return $pdf->download('document.pdf');
```

## 🏃 Menjalankan Aplikasi

### Development

Jalankan server development Laravel dan Vite secara bersamaan:

```bash
composer run dev
```

Atau jalankan secara terpisah:

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server
npm run dev
```

### Production

1. Build assets production:

```bash
npm run build
```

2. Optimize Laravel:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🐛 Troubleshooting

### Error: "No application encryption key has been specified"

```bash
php artisan key:generate
```

### Error: "Class 'Barryvdh\DomPDF\ServiceProvider' not found"

```bash
composer dump-autoload
php artisan config:clear
```

### Tailwind CSS tidak ter-compile

```bash
npm run build
php artisan view:clear
```



##  Developer

Dikembangkan dengan ❤️ menggunakan Laravel & Tailwind CSS
