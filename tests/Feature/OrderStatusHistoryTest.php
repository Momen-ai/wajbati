<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderStatusHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_chef_status_update_creates_history_record_and_queues_notification(): void
    {
        Notification::fake();

        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 45,
            'address' => 'Gaza Main St',
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($chef)->patch(route('chef.orders.update-status', $order), [
            'status' => 'accepted',
        ]);

        $response->assertRedirect();
        $this->assertEquals('accepted', $order->fresh()->status);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'accepted',
            'user_id' => $chef->id,
            'forced' => 0,
        ]);

        Notification::assertSentTo($user, OrderStatusUpdatedNotification::class);
    }

    public function test_admin_cannot_make_invalid_status_transition_without_force(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 45,
            'address' => 'Gaza Main St',
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);

        // Attempt invalid skip from pending directly to completed without force
        $response = $this->actingAs($admin)->put(route('dashboard.orders.update', $order), [
            'status' => 'completed',
            'force' => 0,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('pending', $order->fresh()->status);
        $this->assertDatabaseMissing('order_status_histories', [
            'order_id' => $order->id,
            'to_status' => 'completed',
        ]);
    }

    public function test_admin_can_force_status_transition_and_logs_history(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $chef = User::factory()->create(['role' => 'chef']);
        $user = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id' => $user->id,
            'chef_id' => $chef->id,
            'total' => 45,
            'address' => 'Gaza Main St',
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($admin)->put(route('dashboard.orders.update', $order), [
            'status' => 'completed',
            'force' => 1,
            'note' => 'Customer confirmed delivery offline.',
        ]);

        $response->assertRedirect(route('dashboard.orders.index'));
        $this->assertEquals('completed', $order->fresh()->status);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'completed',
            'user_id' => $admin->id,
            'forced' => 1,
            'note' => 'Customer confirmed delivery offline.',
        ]);

        Notification::assertSentTo($user, OrderStatusUpdatedNotification::class);
    }
}
