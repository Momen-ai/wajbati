<?php

namespace Tests\Feature;

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCancelAndReorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_cancel_pending_order(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 50,
            'delivery_fee' => 3,
            'total' => 53,
            'address' => 'Customer St.',
            'phone' => '0599000111',
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($customer)->post(route('orders.cancel', $order->id));
        $response->assertSessionHas('success', __('Order cancelled successfully.'));

        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'cancelled',
        ]);
    }

    public function test_customer_cannot_cancel_preparing_order(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 50,
            'delivery_fee' => 3,
            'total' => 53,
            'address' => 'Customer St.',
            'phone' => '0599000111',
            'status' => 'preparing',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($customer)->post(route('orders.cancel', $order->id));
        $response->assertSessionHas('error', __('Only pending orders can be cancelled.'));

        $this->assertEquals('preparing', $order->fresh()->status);
    }

    public function test_customer_can_reorder_items_from_completed_order(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $chef = User::factory()->create(['role' => 'chef']);

        $meal = Meal::create([
            'chef_id' => $chef->id,
            'name' => 'Palestinian Maqluba',
            'price' => 35,
            'is_available' => true,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 70,
            'delivery_fee' => 3,
            'total' => 73,
            'address' => 'Customer St.',
            'phone' => '0599000111',
            'status' => 'completed',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'meal_id' => $meal->id,
            'meal_name' => $meal->name,
            'price' => $meal->price,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($customer)->post(route('orders.reorder', $order->id));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', __('Items added to your cart.'));

        // Verify cart session contains the reordered meal with quantity 2
        $cart = session()->get('cart');
        $this->assertArrayHasKey($meal->id, $cart);
        $this->assertEquals(2, $cart[$meal->id]);
    }
}
