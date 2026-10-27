<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationMatrixTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $chef;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->chef = User::factory()->create([
            'role' => 'chef',
            'email_verified_at' => now(),
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Admin Dashboard routes: accessible only by admin.
     */
    public function test_admin_dashboard_authorization_matrix(): void
    {
        $adminRoutes = [
            route('dashboard'),
            route('dashboard.category.index'),
            route('dashboard.meals.index'),
            route('dashboard.orders.index'),
            route('dashboard.contacts.index'),
            route('dashboard.users.index'),
        ];

        foreach ($adminRoutes as $url) {
            // Guest -> 302 redirect to login
            $this->get($url)->assertRedirect(route('login'));

            // Regular User -> 403 Forbidden
            $this->actingAs($this->user)->get($url)->assertForbidden();

            // Chef -> 403 Forbidden
            $this->actingAs($this->chef)->get($url)->assertForbidden();

            // Admin -> 200 OK
            $this->actingAs($this->admin)->get($url)->assertOk();

            auth()->logout();
        }
    }

    /**
     * Chef Portal routes: accessible only by chef.
     */
    public function test_chef_portal_authorization_matrix(): void
    {
        $chefRoutes = [
            route('chef.dashboard'),
            route('chef.meals.index'),
            route('chef.meals.create'),
            route('chef.orders.index'),
        ];

        foreach ($chefRoutes as $url) {
            // Guest -> 302 redirect to login
            $this->get($url)->assertRedirect(route('login'));

            // Regular User -> 403 Forbidden
            $this->actingAs($this->user)->get($url)->assertForbidden();

            // Admin -> 403 Forbidden (unless role is chef)
            $this->actingAs($this->admin)->get($url)->assertForbidden();

            // Chef -> 200 OK
            $this->actingAs($this->chef)->get($url)->assertOk();

            auth()->logout();
        }
    }

    /**
     * Authenticated Customer routes: accessible by logged-in users.
     */
    public function test_customer_routes_authorization_matrix(): void
    {
        $authRoutes = [
            route('orders.index'),
            route('profile.edit'),
        ];

        foreach ($authRoutes as $url) {
            // Guest -> 302 redirect to login
            $this->get($url)->assertRedirect(route('login'));

            // Regular User -> 200 OK
            $this->actingAs($this->user)->get($url)->assertOk();

            // Chef -> 200 OK (has profile & customer order view)
            $this->actingAs($this->chef)->get($url)->assertOk();

            auth()->logout();
        }
    }

    /**
     * Public storefront routes: accessible by everyone.
     */
    public function test_public_routes_accessible_to_all(): void
    {
        $publicRoutes = [
            route('home'),
            route('meals.index'),
            route('chefs.index'),
            route('contact'),
            route('cart.index'),
        ];

        foreach ($publicRoutes as $url) {
            $this->get($url)->assertOk();
            $this->actingAs($this->user)->get($url)->assertOk();
            $this->actingAs($this->chef)->get($url)->assertOk();
            $this->actingAs($this->admin)->get($url)->assertOk();
            auth()->logout();
        }
    }
}
