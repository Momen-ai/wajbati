<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Meal;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class MealSeeder extends Seeder
{
    public function run(): void
    {
        $chef = User::where('role', 'chef')->first();

        if (!$chef) {
             $chef = User::create([
                'name' => 'Chef Ali',
                'email' => 'chef@gmail.com',
                'password' => Hash::make('123456789'),
                'role' => 'chef',
                'phone' => '0599123456',
                'kitchen_name' => 'Ali Kitchen',
                'address' => 'Gaza, Palestine'
            ]);
        }

        $category = Category::first();

        Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Traditional Maqluba',
            'description' => 'A famous Palestinian dish with rice, chicken, and vegetables.',
            'price' => 15.50,
            'number_persons' => 4,
        ]);

        Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Hummus with Meat',
            'description' => 'Creamy hummus topped with roasted spiced meat.',
            'price' => 8.00,
            'number_persons' => 2,
        ]);
    }
}
