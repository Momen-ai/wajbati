<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrder(string $status): Order
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);

        return Order::create([
            'user_id'        => $user->id,
            'chef_id'        => $chef->id,
            'total'          => 50,
            'address'        => 'Test Address',
            'status'         => $status,
            'payment_method' => 'cash',
        ]);
    }

    // ─── Allowed transitions ────────────────────────────────────────────────

    /** @dataProvider allowedTransitionsProvider */
    public function test_allowed_transition_succeeds(string $from, string $to): void
    {
        $order = $this->makeOrder($from);

        $this->actingAs($order->chef);

        $response = $this->patch(route('chef.orders.update-status', $order), ['status' => $to]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals($to, $order->fresh()->status);
    }

    public static function allowedTransitionsProvider(): array
    {
        return [
            'pending → accepted'   => ['pending',   'accepted'],
            'pending → rejected'   => ['pending',   'rejected'],
            'pending → cancelled'  => ['pending',   'cancelled'],
            'accepted → preparing' => ['accepted',  'preparing'],
            'accepted → rejected'  => ['accepted',  'rejected'],
            'accepted → cancelled' => ['accepted',  'cancelled'],
            'preparing → prepared' => ['preparing', 'prepared'],
            'prepared → delivered' => ['prepared',  'delivered'],
            'delivered → completed'=> ['delivered', 'completed'],
        ];
    }

    // ─── Forbidden transitions ───────────────────────────────────────────────

    /** @dataProvider forbiddenTransitionsProvider */
    public function test_forbidden_transition_returns_error(string $from, string $to): void
    {
        $order = $this->makeOrder($from);

        $this->actingAs($order->chef);

        $response = $this->patch(route('chef.orders.update-status', $order), ['status' => $to]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        // Status must NOT have changed
        $this->assertEquals($from, $order->fresh()->status);
    }

    public static function forbiddenTransitionsProvider(): array
    {
        return [
            'pending → delivered (skip)'    => ['pending',   'delivered'],
            'pending → completed (skip)'    => ['pending',   'completed'],
            'pending → preparing (skip)'    => ['pending',   'preparing'],
            'preparing → delivered (skip)'  => ['preparing', 'delivered'],
            'preparing → completed (skip)'  => ['preparing', 'completed'],
            'preparing → accepted (back)'   => ['preparing', 'accepted'],
            'prepared → accepted (back)'    => ['prepared',  'accepted'],
            'completed → pending (restart)' => ['completed', 'pending'],
            'completed → accepted (restart)'=> ['completed', 'accepted'],
            'rejected → pending (reopen)'   => ['rejected',  'pending'],
            'cancelled → pending (reopen)'  => ['cancelled', 'pending'],
            'delivered → preparing (back)'  => ['delivered', 'preparing'],
        ];
    }

    // ─── Ownership: chef B cannot touch chef A's order ───────────────────────

    public function test_chef_cannot_update_status_of_another_chefs_order(): void
    {
        $order    = $this->makeOrder('pending');
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef);

        $response = $this->patch(route('chef.orders.update-status', $order), ['status' => 'accepted']);

        $response->assertStatus(403);
        $this->assertEquals('pending', $order->fresh()->status);
    }

    public function test_chef_cannot_view_another_chefs_order(): void
    {
        $order    = $this->makeOrder('pending');
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef);

        $response = $this->get(route('chef.orders.show', $order));

        $response->assertStatus(403);
    }

    // ─── OrderStatusService unit-level checks ────────────────────────────────

    public function test_order_status_service_canTransitionTo_returns_true_for_valid(): void
    {
        $this->assertTrue(OrderStatusService::canTransitionTo('pending', 'accepted'));
        $this->assertTrue(OrderStatusService::canTransitionTo('accepted', 'preparing'));
        $this->assertTrue(OrderStatusService::canTransitionTo('preparing', 'prepared'));
    }

    public function test_order_status_service_canTransitionTo_returns_false_for_invalid(): void
    {
        $this->assertFalse(OrderStatusService::canTransitionTo('pending', 'delivered'));
        $this->assertFalse(OrderStatusService::canTransitionTo('completed', 'pending'));
        $this->assertFalse(OrderStatusService::canTransitionTo('cancelled', 'accepted'));
    }
}
