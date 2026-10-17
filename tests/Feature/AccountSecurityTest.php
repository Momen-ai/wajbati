<?php

namespace Tests\Feature;

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_create_with_role_admin_does_not_set_admin(): void
    {
        $user = User::create([
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'phone' => '0591234567',
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $this->assertNotEquals('admin', $user->fresh()->role);
        $this->assertEquals('user', $user->fresh()->role);
    }

    public function test_last_admin_cannot_be_deleted_by_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        // Must reject or prevent deleting the sole administrator
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_admin_cannot_be_deleted_or_demoted_in_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Attempt delete
        $delResponse = $this->actingAs($admin)->delete(route('dashboard.users.destroy', $admin));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Attempt demote
        $updateResponse = $this->actingAs($admin)->put(route('dashboard.users.update', $admin), [
            'name' => 'Admin Renamed',
            'email' => $admin->email,
            'role' => 'user',
        ]);

        $this->assertEquals('admin', $admin->fresh()->role);
    }

    public function test_deleting_meal_does_not_delete_order_items_from_completed_order(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);
        $meal = Meal::factory()->create(['chef_id' => $chef->id, 'name' => 'Delicious Shawarma', 'price' => 25]);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'total' => 28,
            'status' => 'completed',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'meal_id' => $meal->id,
            'quantity' => 1,
            'price' => 25,
            'meal_name' => 'Delicious Shawarma',
        ]);

        $meal->delete();

        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'order_id' => $order->id,
            'meal_name' => 'Delicious Shawarma',
        ]);
    }
}
