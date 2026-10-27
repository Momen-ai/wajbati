<?php

namespace App\Services;

use App\Models\Meal;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Get the raw cart items from session: [meal_id => quantity].
     */
    public function get(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Add or increment quantity of a meal.
     */
    public function add(int $mealId, int $quantity): array
    {
        $cart = $this->get();
        $maxQty = config('shop.max_qty_per_item', 20);

        $currentQty = $cart[$mealId] ?? 0;
        $newQty = min($currentQty + $quantity, $maxQty);
        $cart[$mealId] = $newQty;

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    /**
     * Update quantity for a given meal.
     */
    public function update(int $mealId, int $quantity): array
    {
        $cart = $this->get();
        $maxQty = config('shop.max_qty_per_item', 20);

        if ($quantity <= 0) {
            unset($cart[$mealId]);
        } else {
            $cart[$mealId] = min($quantity, $maxQty);
        }

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    /**
     * Remove a meal from the cart.
     */
    public function remove(int $mealId): array
    {
        $cart = $this->get();
        unset($cart[$mealId]);
        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Get detailed, live database representations of the cart items,
     * grouped by chef with subtotal, per-chef delivery fee, and grand total.
     * Uses OrderService::quote as the single source of truth.
     */
    public function detailed(): Quote
    {
        return app(OrderService::class)->quote($this->get());
    }
}
