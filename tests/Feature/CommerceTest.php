<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_add_rejects_negative_or_invalid_quantity(): void
    {
        $meal = Meal::factory()->create();

        // Negative quantity
        $response = $this->postJson(route('cart.add'), [
            'meal_id' => $meal->id,
            'quantity' => -5,
        ]);
        $response->assertStatus(422);

        // String quantity
        $response = $this->postJson(route('cart.add'), [
            'meal_id' => $meal->id,
            'quantity' => 'abc',
        ]);
        $response->assertStatus(422);

        // Excess quantity
        $response = $this->postJson(route('cart.add'), [
            'meal_id' => $meal->id,
            'quantity' => 999999,
        ]);
        $response->assertStatus(422);
    }

    public function test_cart_update_with_unknown_id_returns_422(): void
    {
        $response = $this->patchJson(route('cart.update'), [
            'id' => 99999,
            'quantity' => 2,
        ]);

        $response->assertStatus(422);
    }

    public function test_checkout_recalculates_live_db_price(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);
        $meal = Meal::factory()->create(['chef_id' => $chef->id, 'price' => 10.00]);

        // Add to cart with initial price 10.00
        $this->actingAs($customer)->post(route('cart.add'), [
            'meal_id' => $meal->id,
            'quantity' => 1,
        ]);

        // Update DB price directly to 50.00
        $meal->update(['price' => 50.00]);

        // Checkout
        $response = $this->actingAs($customer)->post(route('orders.store'), [
            'phone' => '0599111222',
            'address' => 'Gaza City',
            'payment_method' => 'cash',
        ]);

        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        // Subtotal must be 50.00, not stale 10.00
        $this->assertEquals(50.00, (float) $order->subtotal);
        $this->assertEquals('0599111222', $order->phone);
    }

    public function test_checkout_multi_chef_charges_delivery_fee_per_chef(): void
    {
        $chef1 = User::factory()->create(['role' => 'chef']);
        $chef2 = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);

        $meal1 = Meal::factory()->create(['chef_id' => $chef1->id, 'price' => 20.00]);
        $meal2 = Meal::factory()->create(['chef_id' => $chef2->id, 'price' => 30.00]);

        $this->actingAs($customer)->post(route('cart.add'), ['meal_id' => $meal1->id, 'quantity' => 1]);
        $this->actingAs($customer)->post(route('cart.add'), ['meal_id' => $meal2->id, 'quantity' => 1]);

        $response = $this->actingAs($customer)->post(route('orders.store'), [
            'phone' => '0599000111',
            'address' => 'Palestine St',
            'payment_method' => 'cash',
        ]);

        $orders = Order::where('user_id', $customer->id)->get();
        $this->assertCount(2, $orders);

        $order1 = $orders->firstWhere('chef_id', $chef1->id);
        $order2 = $orders->firstWhere('chef_id', $chef2->id);

        $this->assertEquals(20.00, (float) $order1->subtotal);
        $this->assertEquals(3.00, (float) $order1->delivery_fee);
        $this->assertEquals(23.00, (float) $order1->total);

        $this->assertEquals(30.00, (float) $order2->subtotal);
        $this->assertEquals(3.00, (float) $order2->delivery_fee);
        $this->assertEquals(33.00, (float) $order2->total);
    }

    public function test_checkout_rejects_another_users_address_id(): void
    {
        $customer1 = User::factory()->create(['role' => 'user']);
        $customer2 = User::factory()->create(['role' => 'user']);
        $otherAddress = Address::create([
            'user_id' => $customer2->id,
            'address' => 'Private Villa',
            'city' => 'Gaza',
            'type' => 'home',
        ]);

        $meal = Meal::factory()->create();
        $this->actingAs($customer1)->post(route('cart.add'), ['meal_id' => $meal->id, 'quantity' => 1]);

        $response = $this->actingAs($customer1)->post(route('orders.store'), [
            'address_id' => $otherAddress->id,
            'phone' => '0599111222',
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHasErrors('address_id');
        $this->assertDatabaseMissing('orders', ['user_id' => $customer1->id]);
    }

    public function test_card_payment_method_is_disabled_and_rejected(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $meal = Meal::factory()->create();
        $this->actingAs($customer)->post(route('cart.add'), ['meal_id' => $meal->id, 'quantity' => 1]);

        $response = $this->actingAs($customer)->post(route('orders.store'), [
            'address' => 'Main St',
            'phone' => '0599111222',
            'payment_method' => 'card',
        ]);

        $response->assertSessionHasErrors('payment_method');
    }

    public function test_cash_order_payment_status_lifecycle(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);
        $meal = Meal::factory()->create(['chef_id' => $chef->id, 'price' => 20]);

        $this->actingAs($customer)->post(route('cart.add'), ['meal_id' => $meal->id, 'quantity' => 1]);
        $this->actingAs($customer)->post(route('orders.store'), [
            'address' => 'Main St',
            'phone' => '0599111222',
            'payment_method' => 'cash',
        ]);

        $order = Order::where('user_id', $customer->id)->first();
        $this->assertEquals('pending', $order->payment_status);

        // Chef transitions order to delivered
        $this->actingAs($chef)->patch(route('chef.orders.update-status', $order), ['status' => 'accepted']);
        $this->actingAs($chef)->patch(route('chef.orders.update-status', $order), ['status' => 'preparing']);
        $this->actingAs($chef)->patch(route('chef.orders.update-status', $order), ['status' => 'prepared']);
        $this->actingAs($chef)->patch(route('chef.orders.update-status', $order), ['status' => 'delivered']);

        $this->assertEquals('paid', $order->fresh()->payment_status);
    }
}
