<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_orders_to_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);

        Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 45.00,
            'subtotal' => 42.00,
            'delivery_fee' => 3.00,
            'status' => 'completed',
            'payment_status' => 'paid',
            'address' => 'Gaza',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.orders.export-csv'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Total,Status,Date', $response->streamedContent());
        $this->assertStringContainsString('45.00', $response->streamedContent());
    }

    public function test_admin_can_view_order_status_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);

        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 25.00,
            'subtotal' => 22.00,
            'delivery_fee' => 3.00,
            'status' => 'preparing',
            'payment_status' => 'pending',
            'address' => 'Gaza',
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'preparing',
            'user_id' => $chef->id,
            'note' => 'Cooking in progress',
            'forced' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.audit-log.index'));

        $response->assertStatus(200);
        $response->assertSee(__('Order Status Audit Log'));
        $response->assertSee('Cooking in progress');
    }
}
