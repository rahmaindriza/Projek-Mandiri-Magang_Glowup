<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Membuat data kategori awal
        Category::create(['name' => 'Serum']);
        Category::create(['name' => 'Sunscreen']);
        Category::create(['name' => 'Moisturizer']);
    }
}
