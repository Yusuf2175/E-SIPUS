<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create petugas user
        User::create([
            'name' => 'Library Staff',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Seed categories 
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
