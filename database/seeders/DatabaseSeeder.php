<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if (app()->isProduction()) {
            if (empty($adminEmail) || empty($adminPassword)) {
                throw new RuntimeException('ADMIN_EMAIL and ADMIN_PASSWORD environment variables are required in production.');
            }
        } else {
            $adminEmail = $adminEmail ?: 'admin@wajbati.local';
            if (empty($adminPassword)) {
                $adminPassword = Str::random(16);
                $this->command?->warn("Generated random admin password for local development: {$adminPassword}");
            }
        }

        $admin = User::firstOrNew(['email' => $adminEmail]);
        $admin->name = 'Wajbati Admin';
        $admin->phone = $admin->phone ?: '0590000000';
        $admin->password = Hash::make($adminPassword);
        $admin->role = 'admin';
        $admin->save();

        $this->call([
            CategorySeeder::class,
        ]);

        if (! app()->isProduction()) {
            $this->call([
                DemoSeeder::class,
            ]);
        }
    }
}
