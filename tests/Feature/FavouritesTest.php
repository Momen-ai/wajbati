<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavouritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_or_toggle_favourites(): void
    {
        $this->get(route('favourites.index'))->assertRedirect(route('login'));
    }

    public function test_customer_can_toggle_favourite_meal(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);
        $category = Category::create(['name' => 'Pastries']);

        $meal = Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Musakhan Rolls',
            'price' => 15,
            'is_available' => true,
        ]);

        // 1. Add meal to favourites
        $response = $this->actingAs($customer)->post(route('favourites.meal.toggle', $meal));
        $response->assertSessionHas('success', __('Meal added to favourites.'));
        $this->assertDatabaseHas('favourites', [
            'user_id' => $customer->id,
            'meal_id' => $meal->id,
            'chef_id' => $chef->id,
        ]);

        // 2. Favourites page displays meal
        $indexResponse = $this->actingAs($customer)->get(route('favourites.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Musakhan Rolls');

        // 3. Toggle again removes meal from favourites
        $response2 = $this->actingAs($customer)->post(route('favourites.meal.toggle', $meal));
        $response2->assertSessionHas('success', __('Meal removed from favourites.'));
        $this->assertDatabaseMissing('favourites', [
            'user_id' => $customer->id,
            'meal_id' => $meal->id,
        ]);
    }

    public function test_customer_can_toggle_favourite_chef(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef', 'name' => 'Chef Um Ahmad']);

        // 1. Add chef to favourites
        $response = $this->actingAs($customer)->post(route('favourites.chef.toggle', $chef));
        $response->assertSessionHas('success', __('Chef added to favourites.'));
        $this->assertDatabaseHas('favourites', [
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'meal_id' => null,
        ]);

        // 2. Favourites page displays chef
        $indexResponse = $this->actingAs($customer)->get(route('favourites.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Chef Um Ahmad');

        // 3. Toggle again removes chef
        $this->actingAs($customer)->post(route('favourites.chef.toggle', $chef));
        $this->assertDatabaseMissing('favourites', [
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'meal_id' => null,
        ]);
    }
}
