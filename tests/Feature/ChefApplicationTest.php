<?php

namespace Tests\Feature;

use App\Models\ChefApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChefApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_chef_application_form(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('chef.apply'));

        $response->assertStatus(200);
        $response->assertSee(__('Apply to Become a Chef'));
    }

    public function test_customer_can_submit_chef_application(): void
    {
        $user = User::factory()->create(['role' => 'user', 'phone' => '0599123456']);

        $response = $this->actingAs($user)->post(route('chef.apply.store'), [
            'kitchen_name' => 'Um Ahmad Delights',
            'bio' => 'Authentic homemade pastry and savory meals.',
            'phone' => '0599123456',
            'address' => 'Gaza, Remal',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('chef_applications', [
            'user_id' => $user->id,
            'kitchen_name' => 'Um Ahmad Delights',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_chef_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $application = ChefApplication::create([
            'user_id' => $user->id,
            'kitchen_name' => 'Chef Master Kitchen',
            'bio' => 'Professional home cooking.',
            'phone' => '0599000111',
            'address' => 'Gaza City',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('dashboard.chef-applications.approve', $application));

        $response->assertRedirect();
        $this->assertEquals('approved', $application->fresh()->status);
        $this->assertEquals('chef', $user->fresh()->role);
    }

    public function test_admin_can_reject_chef_application(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $application = ChefApplication::create([
            'user_id' => $user->id,
            'kitchen_name' => 'Incomplete Kitchen',
            'bio' => 'Bio info',
            'phone' => '0599000222',
            'address' => 'Gaza',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('dashboard.chef-applications.reject', $application));

        $response->assertRedirect();
        $this->assertEquals('rejected', $application->fresh()->status);
        $this->assertEquals('user', $user->fresh()->role);
    }
}
