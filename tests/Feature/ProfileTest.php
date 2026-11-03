<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile page is displayed for chef without lazy loading violation', function () {
    \Illuminate\Database\Eloquent\Model::preventLazyLoading(true);
    $chef = User::factory()->chef()->create();
    $chef = User::find($chef->id);

    $response = $this
        ->actingAs($chef)
        ->get('/profile');

    $response->assertOk();
});

test('test meal card chef image does not throw lazy loading violation', function () {
    \Illuminate\Database\Eloquent\Model::preventLazyLoading(true);
    $chef1 = User::factory()->chef()->create();
    $chef2 = User::factory()->chef()->create();
    \App\Models\Meal::factory()->create(['chef_id' => $chef1->id]);
    \App\Models\Meal::factory()->create(['chef_id' => $chef2->id]);

    // Query meals like HomeController does: with(['image', 'chef', 'category'])
    $meals = \App\Models\Meal::with(['image', 'chef', 'category'])->get();

    // Render meal-card component
    $view = $this->blade('<x-ui.meal-card :meal="$meal" />', ['meal' => $meals->first()]);
    $this->assertNotEmpty($view);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull(User::find($user->id));
    $this->assertTrue($user->fresh()->trashed());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
