<?php

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('chef can create meal with number_persons, prep_time_minutes, and is_available', function () {
    Storage::fake('public');
    $chef = User::factory()->create(['role' => 'chef']);
    $category = Category::factory()->create();

    $response = $this->actingAs($chef)->post(route('chef.meals.store'), [
        'name' => 'Mansaf',
        'price' => 35.00,
        'number_persons' => 6,
        'prep_time_minutes' => 90,
        'is_available' => '1',
        'category_id' => $category->id,
        'description' => 'Traditional festive meal',
        'image' => UploadedFile::fake()->image('mansaf.jpg'),
    ]);

    $response->assertRedirect(route('chef.meals.index'));

    $meal = Meal::where('name', 'Mansaf')->first();
    expect($meal)->not->toBeNull()
        ->and($meal->number_persons)->toBe(6)
        ->and($meal->prep_time_minutes)->toBe(90)
        ->and($meal->is_available)->toBeTrue();
});

test('chef can update meal availability to false', function () {
    $chef = User::factory()->create(['role' => 'chef']);
    $category = Category::factory()->create();
    $meal = Meal::factory()->create([
        'chef_id' => $chef->id,
        'category_id' => $category->id,
        'is_available' => true,
    ]);

    $response = $this->actingAs($chef)->patch(route('chef.meals.update', $meal), [
        'name' => $meal->name,
        'price' => $meal->price,
        'category_id' => $category->id,
        'number_persons' => 4,
        'prep_time_minutes' => 30,
        // is_available omitted -> unchecked checkbox
    ]);

    $response->assertRedirect(route('chef.meals.index'));
    expect($meal->fresh()->is_available)->toBeFalse();
});

test('unavailable meals are hidden from storefront meals index', function () {
    $availableMeal = Meal::factory()->create(['is_available' => true, 'name' => 'Available Shawarma']);
    $unavailableMeal = Meal::factory()->create(['is_available' => false, 'name' => 'Hidden Falafel']);

    $response = $this->get(route('meals.index'));
    $response->assertOk();

    $response->assertSee('Available Shawarma');
    $response->assertDontSee('Hidden Falafel');
});

test('checkout rejects meal if it became unavailable', function () {
    $chef = User::factory()->create(['role' => 'chef']);
    $customer = User::factory()->create(['role' => 'user']);
    $meal = Meal::factory()->create([
        'chef_id' => $chef->id,
        'price' => 20.00,
        'is_available' => true,
    ]);

    $this->actingAs($customer);
    session(['cart' => [$meal->id => 1]]);

    // Chef marks meal unavailable
    $meal->update(['is_available' => false]);

    $response = $this->post(route('orders.store'), [
        'address' => 'Gaza Omar Mukhtar St',
        'phone' => '0599123456',
        'payment_method' => 'cash',
    ]);

    $response->assertSessionHasErrors('cart');
});
