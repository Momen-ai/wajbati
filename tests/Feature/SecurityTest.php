<?php

namespace Tests\Feature;

use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    // ─── 1. Role Escalation ──────────────────────────────────────────────────

    public function test_registration_with_role_admin_payload_always_creates_user_role(): void
    {
        $this->post('/register', [
            'name'                  => 'Attacker',
            'email'                 => 'attacker@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'phone'                 => '0599001001',
            'role'                  => 'admin',
        ]);

        $this->assertEquals('user', User::where('email', 'attacker@example.com')->value('role'));
    }

    // ─── 2. Dashboard access control ─────────────────────────────────────────

    public function test_regular_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertStatus(403);
    }

    public function test_chef_cannot_access_admin_dashboard(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($chef)
            ->get(route('dashboard'))
            ->assertStatus(403);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    // ─── 3. Chef cross-access: another chef's meal ───────────────────────────

    private function makeChefAndMeal(): array
    {
        $category = Category::create(['name' => 'Test Category']);
        $chef     = User::factory()->create(['role' => 'chef']);
        $meal     = Meal::create([
            'chef_id'     => $chef->id,
            'category_id' => $category->id,
            'name'        => 'Test Meal',
            'price'       => 10,
        ]);

        return [$chef, $meal];
    }

    public function test_chef_cannot_edit_another_chefs_meal(): void
    {
        [, $meal]  = $this->makeChefAndMeal();
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef)
            ->get(route('chef.meals.edit', $meal))
            ->assertStatus(403);
    }

    public function test_chef_cannot_update_another_chefs_meal(): void
    {
        [$chef, $meal] = $this->makeChefAndMeal();
        $otherChef     = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef)
            ->patch(route('chef.meals.update', $meal), [
                'name'        => 'Hijacked Meal',
                'price'       => 99,
                'category_id' => $meal->category_id,
            ])
            ->assertStatus(403);

        // Original name must remain unchanged
        $this->assertEquals('Test Meal', $meal->fresh()->name);
    }

    public function test_chef_cannot_delete_another_chefs_meal(): void
    {
        [, $meal]  = $this->makeChefAndMeal();
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef)
            ->delete(route('chef.meals.destroy', $meal))
            ->assertStatus(403);

        $this->assertDatabaseHas('meals', ['id' => $meal->id]);
    }

    // ─── 4. Chef cross-access: another chef's order ──────────────────────────

    private function makeChefAndOrder(): array
    {
        $chef  = User::factory()->create(['role' => 'chef']);
        $user  = User::factory()->create(['role' => 'user']);
        $order = Order::create([
            'user_id'        => $user->id,
            'chef_id'        => $chef->id,
            'total'          => 50,
            'address'        => 'Test Address',
            'status'         => 'pending',
            'payment_method' => 'cash',
        ]);

        return [$chef, $order];
    }

    public function test_chef_cannot_view_another_chefs_order(): void
    {
        [, $order] = $this->makeChefAndOrder();
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef)
            ->get(route('chef.orders.show', $order))
            ->assertStatus(403);
    }

    public function test_chef_cannot_update_status_of_another_chefs_order(): void
    {
        [, $order] = $this->makeChefAndOrder();
        $otherChef = User::factory()->create(['role' => 'chef']);

        $this->actingAs($otherChef)
            ->patch(route('chef.orders.update-status', $order), ['status' => 'accepted'])
            ->assertStatus(403);

        $this->assertEquals('pending', $order->fresh()->status);
    }
}
