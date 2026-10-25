<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            throw new RuntimeException('DemoSeeder cannot be executed in production environment.');
        }

        $chef = User::where('role', 'chef')->first();

        if (! $chef) {
            $demoPassword = env('DEMO_PASSWORD');
            if (empty($demoPassword)) {
                $demoPassword = Str::random(16);
                $this->command?->warn("Generated random demo chef password: {$demoPassword}");
            }

            $chef = new User([
                'name' => 'Chef Ali',
                'email' => 'chef@example.com',
                'password' => Hash::make($demoPassword),
                'phone' => '0599123456',
                'kitchen_name' => 'Ali Kitchen',
                'address' => 'Gaza, Palestine',
                'email_verified_at' => now(),
            ]);
            $chef->role = 'chef';
            $chef->save();
        }

        $category = Category::firstOrCreate(['name' => 'Palestinian Traditional']);

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
