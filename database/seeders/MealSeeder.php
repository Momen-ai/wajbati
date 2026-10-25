<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Seed meals for an existing chef.
     * Note: MealSeeder does NOT create user accounts; user creation belongs strictly to DemoSeeder.
     */
    public function run(): void
    {
        $chef = User::where('role', 'chef')->first();

        if (! $chef) {
            return;
        }

        $category = Category::first();
        if (! $category) {
            return;
        }

        Meal::firstOrCreate(
            ['name' => 'Traditional Maqluba', 'chef_id' => $chef->id],
            [
                'category_id' => $category->id,
                'description' => 'A famous Palestinian dish with rice, chicken, and vegetables.',
                'price' => 15.50,
                'number_persons' => 4,
                'is_available' => true,
                'prep_time_minutes' => 45,
            ]
        );

        Meal::firstOrCreate(
            ['name' => 'Hummus with Meat', 'chef_id' => $chef->id],
            [
                'category_id' => $category->id,
                'description' => 'Creamy hummus topped with roasted spiced meat.',
                'price' => 8.00,
                'number_persons' => 2,
                'is_available' => true,
                'prep_time_minutes' => 20,
            ]
        );
    }
}
