<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_order()
    {
        $user = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);
        $category = Category::create(['name' => 'Fast Food']);
        $meal = Meal::create([
            'chef_id' => $chef->id,
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 10,
            'number_persons' => 1
        ]);

        $this->actingAs($user);

        session(['cart' => [
            $meal->id => [
                'name' => $meal->name,
                'quantity' => 2,
                'price' => 10,
                'image' => 'test.jpg'
            ]
        ]]);

        $response = $this->post(route('orders.store'), [
            'address' => 'Test Address',
            'phone' => '0599111222',
            'payment_method' => 'cash'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 23,
        ]);
    }

    public function test_chef_can_update_order_status()
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 50,
            'address' => 'Gaza',
            'status' => 'pending',
            'payment_method' => 'cash'
        ]);

        $this->actingAs($chef);

        $response = $this->patch(route('chef.orders.update-status', $order), [
            'status' => 'accepted'
        ]);

        $response->assertStatus(302);
        $this->assertEquals('accepted', $order->fresh()->status);
    }
}
