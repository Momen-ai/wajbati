<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'phone'                 => '0599000111',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});

test('registering with role=admin in payload always creates a role=user account', function () {
    $response = $this->post('/register', [
        'name'                  => 'Hacker User',
        'email'                 => 'hacker@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'phone'                 => '0599000222',
        'role'                  => 'admin',  // malicious payload
    ]);

    $response->assertRedirect(route('home', absolute: false));
    $this->assertAuthenticated();

    $user = User::where('email', 'hacker@example.com')->firstOrFail();
    expect($user->role)->toBe('user');
});

test('registering with role=chef in payload always creates a role=user account', function () {
    $response = $this->post('/register', [
        'name'                  => 'Fake Chef',
        'email'                 => 'fakechef@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
        'phone'                 => '0599000333',
        'role'                  => 'chef',  // malicious payload
    ]);

    $response->assertRedirect(route('home', absolute: false));
    $this->assertAuthenticated();

    $user = User::where('email', 'fakechef@example.com')->firstOrFail();
    expect($user->role)->toBe('user');
});

