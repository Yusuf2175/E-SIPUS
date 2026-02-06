<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin and petugas users
        $admin = User::where('role', 'admin')->first();
        $petugas = User::where('role', 'petugas')->first();
        
        // If no admin/petugas exists, create one
        if (!$admin) {
            $admin = User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }
        
        if (!$petugas) {
            $petugas = User::create([
                'name' => 'Petugas Perpustakaan',
                'email' => 'petugas@example.com',
                'password' => bcrypt('password'),
                'role' => 'petugas'
            ]);
        }

        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '978-602-291-000-1',
                'description' => 'Novel yang menceritakan tentang perjuangan anak-anak di Belitung untuk mendapatkan pendidikan.',
                'category' => 'Fiksi',
                'publisher' => 'Bentang Pustaka',
                'published_year' => 2005,
                'total_copies' => 5,
                'available_copies' => 5,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '978-602-291-000-2',
                'description' => 'Novel pertama dari Tetralogi Buru yang menceritakan kisah Minke dan perjuangannya.',
                'category' => 'Fiksi Sejarah',
                'publisher' => 'Hasta Mitra',
                'published_year' => 1980,
                'total_copies' => 3,
                'available_copies' => 3,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Algoritma dan Pemrograman',
                'author' => 'Rinaldi Munir',
                'isbn' => '978-602-291-000-3',
                'description' => 'Buku panduan lengkap tentang algoritma dan pemrograman untuk mahasiswa informatika.',
                'category' => 'Teknologi',
                'publisher' => 'Informatika',
                'published_year' => 2018,
                'total_copies' => 10,
                'available_copies' => 10,
                'added_by' => $petugas->id
            ],
            [
                'title' => 'Matematika Diskrit',
                'author' => 'Rinaldi Munir',
                'isbn' => '978-602-291-000-4',
                'description' => 'Buku tentang matematika diskrit yang digunakan dalam ilmu komputer.',
                'category' => 'Matematika',
                'publisher' => 'Informatika',
                'published_year' => 2020,
                'total_copies' => 8,
                'available_copies' => 8,
                'added_by' => $petugas->id
            ],
            [
                'title' => 'Ayat-Ayat Cinta',
                'author' => 'Habiburrahman El Shirazy',
                'isbn' => '978-602-291-000-5',
                'description' => 'Novel romantis yang berlatar belakang kehidupan mahasiswa Indonesia di Mesir.',
                'category' => 'Fiksi',
                'publisher' => 'Republika',
                'published_year' => 2004,
                'total_copies' => 4,
                'available_copies' => 4,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'isbn' => '978-602-291-000-6',
                'description' => 'Buku tentang filosofi Stoikisme yang dapat diterapkan dalam kehidupan sehari-hari.',
                'category' => 'Filosofi',
                'publisher' => 'Kompas Gramedia',
                'published_year' => 2019,
                'total_copies' => 6,
                'available_copies' => 6,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Struktur Data dengan Java',
                'author' => 'Bambang Hariyanto',
                'isbn' => '978-602-291-000-7',
                'description' => 'Panduan lengkap struktur data menggunakan bahasa pemrograman Java.',
                'category' => 'Teknologi',
                'publisher' => 'Informatika',
                'published_year' => 2021,
                'total_copies' => 7,
                'available_copies' => 7,
                'added_by' => $petugas->id
            ],
            [
                'title' => 'Sejarah Indonesia Modern',
                'author' => 'M.C. Ricklefs',
                'isbn' => '978-602-291-000-8',
                'description' => 'Buku sejarah Indonesia dari masa kolonial hingga era reformasi.',
                'category' => 'Sejarah',
                'publisher' => 'Serambi',
                'published_year' => 2017,
                'total_copies' => 5,
                'available_copies' => 5,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Psikologi Pendidikan',
                'author' => 'Muhibbin Syah',
                'isbn' => '978-602-291-000-9',
                'description' => 'Buku tentang psikologi dalam konteks pendidikan dan pembelajaran.',
                'category' => 'Psikologi',
                'publisher' => 'Remaja Rosdakarya',
                'published_year' => 2016,
                'total_copies' => 4,
                'available_copies' => 4,
                'added_by' => $petugas->id
            ],
            [
                'title' => 'Ekonomi Makro',
                'author' => 'Sadono Sukirno',
                'isbn' => '978-602-291-001-0',
                'description' => 'Buku teks ekonomi makro untuk mahasiswa ekonomi dan bisnis.',
                'category' => 'Ekonomi',
                'publisher' => 'Rajawali Pers',
                'published_year' => 2019,
                'total_copies' => 6,
                'available_copies' => 6,
                'added_by' => $admin->id
            ],
            [
                'title' => 'Database Management Systems',
                'author' => 'Raghu Ramakrishnan',
                'isbn' => '978-602-291-001-1',
                'description' => 'Comprehensive guide to database management systems and SQL.',
                'category' => 'Teknologi',
                'publisher' => 'McGraw-Hill',
                'published_year' => 2020,
                'total_copies' => 5,
                'available_copies' => 5,
                'added_by' => $petugas->id
            ],
            [
                'title' => 'Manajemen Strategis',
                'author' => 'Fred R. David',
                'isbn' => '978-602-291-001-2',
                'description' => 'Buku tentang konsep dan aplikasi manajemen strategis dalam organisasi.',
                'category' => 'Manajemen',
                'publisher' => 'Salemba Empat',
                'published_year' => 2018,
                'total_copies' => 4,
                'available_copies' => 4,
                'added_by' => $admin->id
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}