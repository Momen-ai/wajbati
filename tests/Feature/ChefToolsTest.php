<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChefToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_chef_can_export_orders_to_csv(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user', 'name' => 'Sara Ahmad']);

        Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 60,
            'delivery_fee' => 3,
            'total' => 63,
            'address' => 'Gaza Main St.',
            'phone' => '0599123456',
            'status' => 'completed',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($chef)->get(route('chef.orders.export'));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('kitchen-orders-', $response->headers->get('Content-Disposition'));

        // Customer & regular user cannot export chef orders
        auth()->logout();
        $this->actingAs($customer)->get(route('chef.orders.export'))->assertForbidden();
    }
}
