<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MealSeeder;
use Illuminate\Support\Facades\App;

test('meal seeder does not create users when run alone', function () {
    $initialUserCount = User::count();

    $seeder = new MealSeeder;
    $seeder->run();

    expect(User::count())->toBe($initialUserCount);
});

test('database seeder in production never creates demo users', function () {
    App::detectEnvironment(fn () => 'production');

    putenv('ADMIN_EMAIL=prod-admin@wajbati.com');
    putenv('ADMIN_PASSWORD=SuperSecretProdPassword123!');

    $seeder = new DatabaseSeeder;
    $seeder->run();

    // Verify only the specified admin was created and no demo chef exists
    expect(User::where('email', 'chef@gmail.com')->exists())->toBeFalse()
        ->and(User::where('email', 'chef@example.com')->exists())->toBeFalse()
        ->and(User::where('email', 'prod-admin@wajbati.com')->exists())->toBeTrue()
        ->and(User::where('role', 'chef')->count())->toBe(0);

    // Reset environment
    App::detectEnvironment(fn () => 'testing');
});
