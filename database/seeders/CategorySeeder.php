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
                'description' => 'A collection of best-selling novels and classic literary works from renowned authors.'
            ],
            [
                'name' => 'Sains & Teknologi',
                'description' => 'The latest books on natural science, computers, and technological innovations.'
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'description' => 'A guide to global business, management, and economics.'
            ],
            [
                'name' => 'Sejarah & Budaya',
                'description' => 'Exploring the past and various cultures of the world.'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
