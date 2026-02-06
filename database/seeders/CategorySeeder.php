<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Novel & Sastra',
                'description' => 'Koleksi novel best-seller dan karya sastra klasik dari penulis ternama'
            ],
            [
                'name' => 'Sains & Teknologi',
                'description' => 'Buku pengetahuan alam, komputer, dan inovasi teknologi terbaru'
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'description' => 'Panduan bisnis, manajemen, dan ekonomi global'
            ],
            [
                'name' => 'Sejarah & Budaya',
                'description' => 'Menjelajahi masa lalu dan ragam budaya dunia'
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Buku pelajaran dan referensi akademik'
            ],
            [
                'name' => 'Agama & Filsafat',
                'description' => 'Kajian keagamaan dan pemikiran filosofis'
            ],
            [
                'name' => 'Anak & Remaja',
                'description' => 'Bacaan untuk anak-anak dan remaja'
            ],
            [
                'name' => 'Komik & Manga',
                'description' => 'Komik lokal dan manga dari Jepang'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
