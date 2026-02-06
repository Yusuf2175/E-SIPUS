<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Buat petugas 
        User::create([
            'name' => 'Petugas Satu',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        // Buat user biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Jalankan BookSeeder
        $this->call([
            BookSeeder::class,
        ]);
    }
}
