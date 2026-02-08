<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Admin User =====
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // شرط البحث
            [
                'name'     => 'Admin',
                'phone'    => '0590000000',
                'password' => Hash::make('123456789'),
                'role'     => 'admin',
            ]
        );

        $this->call([
            CategorySeeder::class,
            MealSeeder::class,
        ]);
    }
}

