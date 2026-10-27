<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Oriental', 'Western', 'Sweets', 'Fast Food', 'Traditional'];
        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category]);
        }
    }
}
