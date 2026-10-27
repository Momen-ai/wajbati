<?php

use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use App\Services\Quote;

test('order service quote creates accurate breakdown for single chef', function () {
    $chef = User::factory()->create(['role' => 'chef']);
    $meal = Meal::factory()->create(['chef_id' => $chef->id, 'price' => 25.00]);

    $orderService = app(OrderService::class);
    $cart = [$meal->id => 2];

    $quote = $orderService->quote($cart);

    expect($quote)->toBeInstanceOf(Quote::class)
        ->and($quote->subtotal)->toBe(50.00)
        ->and($quote->deliveryFee)->toBe(3.00)
        ->and($quote->grandTotal)->toBe(53.00)
        ->and($quote->itemCount)->toBe(2)
        ->and($quote->byChef)->toHaveCount(1);

    $chefQuote = $quote->byChef->first();
    expect($chefQuote['subtotal'])->toBe(50.00)
        ->and($chefQuote['delivery_fee'])->toBe(3.00)
        ->and($chefQuote['total'])->toBe(53.00);
});

test('2-chef cart quote total equals sum of created orders totals and matches cart html', function () {
    $chef1 = User::factory()->create(['role' => 'chef', 'name' => 'Chef Mario']);
    $chef2 = User::factory()->create(['role' => 'chef', 'name' => 'Chef Luigi']);
    $customer = User::factory()->create(['role' => 'user']);

    $meal1 = Meal::factory()->create(['chef_id' => $chef1->id, 'price' => 20.00, 'name' => 'Pasta']);
    $meal2 = Meal::factory()->create(['chef_id' => $chef2->id, 'price' => 30.00, 'name' => 'Pizza']);

    $orderService = app(OrderService::class);
    $cart = [$meal1->id => 1, $meal2->id => 2]; // 20 + 60 = 80 subtotal, 2 chefs * 3 = 6 delivery, total = 86

    $quote = $orderService->quote($cart);

    expect($quote->subtotal)->toBe(80.00)
        ->and($quote->deliveryFee)->toBe(6.00)
        ->and($quote->grandTotal)->toBe(86.00)
        ->and($quote->byChef)->toHaveCount(2);

    // Add to session cart
    $this->actingAs($customer);
    session(['cart' => $cart]);

    // View cart page
    $cartResponse = $this->get(route('cart.index'));
    $cartResponse->assertOk();

    // Verify cart HTML displays the grand total and per-chef delivery fees
    $cartContent = $cartResponse->getContent();
    expect($cartContent)->toContain(money(86.00))
        ->and($cartContent)->not->toContain('$total + 3')
        ->and($cartContent)->not->toContain('$order->total - 3');

    // Checkout
    $checkoutResponse = $this->post(route('orders.store'), [
        'address' => 'Test Street 123',
        'phone' => '0599123456',
        'payment_method' => 'cash',
    ]);
    $checkoutResponse->assertRedirect(route('orders.index'));

    $orders = Order::where('user_id', $customer->id)->get();
    expect($orders)->toHaveCount(2);

    $ordersSum = (float) $orders->sum('total');
    expect($ordersSum)->toBe($quote->grandTotal);

    // View order show page and verify no hardcoded 3 or total - 3
    $order1 = $orders->first();
    $orderResponse = $this->get(route('orders.show', $order1));
    $orderResponse->assertOk();
    $orderContent = $orderResponse->getContent();
    expect($orderContent)->not->toContain('$3.00')
        ->and($orderContent)->toContain(money($order1->subtotal))
        ->and($orderContent)->toContain(money($order1->delivery_fee));
});

test('price changed after add-to-cart is reflected in quote and cart html', function () {
    $chef = User::factory()->create(['role' => 'chef']);
    $customer = User::factory()->create(['role' => 'user']);
    $meal = Meal::factory()->create(['chef_id' => $chef->id, 'price' => 15.00]);

    $this->actingAs($customer);
    session(['cart' => [$meal->id => 1]]);

    // Price updates in DB
    $meal->update(['price' => 45.00]);

    $orderService = app(OrderService::class);
    $quote = $orderService->quote(session('cart'));

    expect($quote->subtotal)->toBe(45.00)
        ->and($quote->grandTotal)->toBe(48.00);

    $response = $this->get(route('cart.index'));
    $response->assertOk();
    $content = $response->getContent();
    expect($content)->toContain(money(48.00));
});

test('checkout double submit is blocked by atomic user lock', function () {
    $chef = User::factory()->create(['role' => 'chef']);
    $customer = User::factory()->create(['role' => 'user']);
    $meal = Meal::factory()->create(['chef_id' => $chef->id, 'price' => 20]);

    $this->actingAs($customer);
    session(['cart' => [$meal->id => 1]]);

    // Simulate concurrent lock held by another in-flight checkout request
    $lock = \Illuminate\Support\Facades\Cache::lock('checkout_user_'.$customer->id, 10);
    $lock->get();

    $response = $this->post(route('orders.store'), [
        'address' => 'Main St',
        'phone' => '0599111222',
        'payment_method' => 'cash',
    ]);

    $response->assertSessionHasErrors('cart');

    $lock->release();
});
