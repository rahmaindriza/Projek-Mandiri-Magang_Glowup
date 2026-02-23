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
    $this->call([
        CategorySeeder::class, // Tambahkan ini
    ]);

    // Akun Admin kamu juga taruh di sini agar tidak hilang terus
    \App\Models\User::create([
        'name' => 'Admin GlowUp',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('12345678'),
        'role' => 'admin',
    ]);
}
}
