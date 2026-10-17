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
     */
    public function detailed(): array
    {
        $cart = $this->get();
        if (empty($cart)) {
            return [
                'by_chef' => collect(),
                'subtotal' => 0.0,
                'total_delivery_fee' => 0.0,
                'grand_total' => 0.0,
                'count' => 0,
            ];
        }

        $mealIds = array_keys($cart);
        $meals = Meal::with(['chef', 'image'])->whereIn('id', $mealIds)->get()->keyBy('id');

        $deliveryFeePerChef = (float) config('shop.delivery_fee', 3.00);
        $byChef = [];
        $overallSubtotal = 0.0;

        foreach ($cart as $mealId => $qty) {
            if (! isset($meals[$mealId])) {
                continue;
            }

            $meal = $meals[$mealId];
            $chefId = $meal->chef_id;
            $lineTotal = (float) $meal->price * (int) $qty;
            $overallSubtotal += $lineTotal;

            if (! isset($byChef[$chefId])) {
                $byChef[$chefId] = [
                    'chef' => $meal->chef,
                    'items' => [],
                    'subtotal' => 0.0,
                    'delivery_fee' => $deliveryFeePerChef,
                    'total' => 0.0,
                ];
            }

            $byChef[$chefId]['items'][] = [
                'meal' => $meal,
                'quantity' => (int) $qty,
                'price' => (float) $meal->price,
                'line_total' => $lineTotal,
            ];

            $byChef[$chefId]['subtotal'] += $lineTotal;
            $byChef[$chefId]['total'] = $byChef[$chefId]['subtotal'] + $deliveryFeePerChef;
        }

        $totalDeliveryFee = count($byChef) * $deliveryFeePerChef;
        $grandTotal = $overallSubtotal + $totalDeliveryFee;

        return [
            'by_chef' => collect($byChef),
            'subtotal' => $overallSubtotal,
            'total_delivery_fee' => $totalDeliveryFee,
            'grand_total' => $grandTotal,
            'count' => array_sum($cart),
        ];
    }
}
