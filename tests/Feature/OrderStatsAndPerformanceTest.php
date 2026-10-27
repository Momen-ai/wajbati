<?php

namespace Tests\Feature;

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderStats;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OrderStatsAndPerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test monthly revenue series uses grouped query correctly.
     */
    public function test_monthly_revenue_series_groups_revenue_properly(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);

        // Delivered order in current month
        Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 100,
            'delivery_fee' => 3,
            'total' => 103,
            'address' => 'Test Address',
            'phone' => '0599000000',
            'status' => 'delivered',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'created_at' => Carbon::now(),
        ]);

        // Pending unpaid order should not be included
        Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 50,
            'delivery_fee' => 3,
            'total' => 53,
            'address' => 'Test Address',
            'phone' => '0599000000',
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'created_at' => Carbon::now(),
        ]);

        $series = OrderStats::monthlyRevenueSeries(6);
        $this->assertCount(6, $series['labels']);
        $this->assertCount(6, $series['values']);
        $this->assertEquals(103.0, end($series['values']));
    }

    /**
     * Test cache is cleared when an order is updated or saved.
     */
    public function test_dashboard_cache_is_cleared_on_order_status_change(): void
    {
        Cache::put('admin_dashboard_stats', ['cached' => true], 300);
        $this->assertTrue(Cache::has('admin_dashboard_stats'));

        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 40,
            'delivery_fee' => 3,
            'total' => 43,
            'address' => 'Test Address',
            'phone' => '0599000000',
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
        ]);

        // Booted hook should invalidate cache on create/update
        $this->assertFalse(Cache::has('admin_dashboard_stats'));

        Cache::put('admin_dashboard_stats', ['cached' => true], 300);
        $order->update(['status' => 'accepted']);
        $this->assertFalse(Cache::has('admin_dashboard_stats'));
    }

    /**
     * Test popular meals prioritization on homepage: 30-day completed orders with rating fallback.
     */
    public function test_popular_meals_prioritizes_recent_completed_orders(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);
        $customer = User::factory()->create(['role' => 'user']);

        $popularMeal = Meal::create([
            'chef_id' => $chef->id,
            'name' => 'Popular Meal',
            'description' => 'Most ordered recently',
            'price' => 25,
            'is_available' => true,
        ]);

        $lessPopularMeal = Meal::create([
            'chef_id' => $chef->id,
            'name' => 'Less Popular Meal',
            'description' => 'Fewer recent orders',
            'price' => 30,
            'is_available' => true,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'chef_id' => $chef->id,
            'subtotal' => 50,
            'delivery_fee' => 3,
            'total' => 53,
            'address' => 'Test Address',
            'phone' => '0599000000',
            'status' => 'completed',
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'meal_id' => $popularMeal->id,
            'meal_name' => $popularMeal->name,
            'price' => $popularMeal->price,
            'quantity' => 2,
        ]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSeeInOrder(['Popular Meal', 'Less Popular Meal']);
    }
}
