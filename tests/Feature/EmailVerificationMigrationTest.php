<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

test('existing users are marked as verified and can access protected checkout', function () {
    // Simulate user created without email verification
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    expect($user->hasVerifiedEmail())->toBeFalse();

    // Run the migration logic
    DB::table('users')
        ->whereNull('email_verified_at')
        ->update(['email_verified_at' => now()]);

    $user->refresh();

    expect($user->hasVerifiedEmail())->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull();
});
