<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    protected $model = Meal::class;

    public function definition(): array
    {
        return [
            'chef_id' => User::factory()->state(['role' => 'chef']),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5, 50),
            'number_persons' => 1,
        ];
    }
}
