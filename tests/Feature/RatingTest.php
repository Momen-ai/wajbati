<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_rate_meal_without_completed_or_delivered_order(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);
        $category = Category::create(['name' => 'Pizza']);
        $meal = Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Pepperoni Pizza',
            'price' => 25,
            'number_persons' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('meals.rate', $meal), [
            'star' => 5,
            'body' => 'Great food!',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('ratings', ['meal_id' => $meal->id]);
    }

    public function test_chef_cannot_rate_own_meal(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $category = Category::create(['name' => 'Burger']);
        $meal = Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Cheeseburger',
            'price' => 15,
            'number_persons' => 1,
        ]);

        $response = $this->actingAs($chef)->post(route('meals.rate', $meal), [
            'star' => 5,
            'body' => 'My own best burger',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('ratings', ['meal_id' => $meal->id]);
    }

    public function test_user_can_rate_meal_with_delivered_order_and_subsequent_rating_updates(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);
        $category = Category::create(['name' => 'Salad']);
        $meal = Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Greek Salad',
            'price' => 12,
            'number_persons' => 1,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 15,
            'address' => 'Gaza City',
            'status' => 'delivered',
            'payment_method' => 'cash',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'meal_id' => $meal->id,
            'meal_name' => $meal->name,
            'quantity' => 1,
            'price' => 12,
        ]);

        // First rating
        $response = $this->actingAs($user)->post(route('meals.rate', $meal), [
            'star' => 4,
            'body' => 'Very fresh salad',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'meal_id' => $meal->id,
            'star' => 4,
            'body' => 'Very fresh salad',
        ]);
        $this->assertEquals(1, Rating::where('meal_id', $meal->id)->count());

        // Update existing rating
        $updateResponse = $this->actingAs($user)->post(route('meals.rate', $meal), [
            'star' => 5,
            'body' => 'Even better next time!',
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals(1, Rating::where('meal_id', $meal->id)->count());
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'meal_id' => $meal->id,
            'star' => 5,
            'body' => 'Even better next time!',
        ]);
    }
}
