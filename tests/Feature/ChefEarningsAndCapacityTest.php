<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChefEarningsAndCapacityTest extends TestCase
{
    use RefreshDatabase;

    public function test_chef_can_view_earnings_report(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);

        Order::create([
            'chef_id' => $chef->id,
            'user_id' => $user->id,
            'total' => 50.00,
            'subtotal' => 47.00,
            'delivery_fee' => 3.00,
            'status' => 'completed',
            'payment_status' => 'paid',
            'address' => 'Gaza',
        ]);

        $response = $this->actingAs($chef)->get(route('chef.earnings'));

        $response->assertStatus(200);
        $response->assertSee(__('Earnings & Performance Report'));
        $response->assertSee(money(50.00));
    }

    public function test_chef_can_update_daily_order_capacity(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);

        $response = $this->actingAs($chef)->post(route('chef.capacity.update'), [
            'daily_capacity' => 25,
        ]);

        $response->assertRedirect();
        $this->assertEquals(25, $chef->fresh()->daily_capacity);
    }
}
