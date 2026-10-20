<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Create orders from the current shopping cart.
     *
     * @throws ValidationException
     */
    public function createOrdersFromCart(User $customer, array $validated): Collection
    {
        $cart = $this->cartService->get();
        if (empty($cart)) {
            throw ValidationException::withMessages([
                'cart' => __('Your cart is empty.'),
            ]);
        }

        $mealIds = array_keys($cart);
        $meals = Meal::with('chef')->whereIn('id', $mealIds)->get()->keyBy('id');

        if ($meals->isEmpty()) {
            $this->cartService->clear();
            throw ValidationException::withMessages([
                'cart' => __('None of the items in your cart are currently available.'),
            ]);
        }

        // Resolve delivery address
        $addressText = $validated['address'] ?? null;
        if (! empty($validated['address_id'])) {
            $savedAddress = Address::where('id', $validated['address_id'])
                ->where('user_id', $customer->id)
                ->first();

            if (! $savedAddress) {
                throw ValidationException::withMessages([
                    'address_id' => __('The selected delivery address does not belong to your account.'),
                ]);
            }

            $addressText = $savedAddress->address.($savedAddress->city ? ', '.$savedAddress->city : '');
        }

        if (empty($addressText)) {
            throw ValidationException::withMessages([
                'address' => __('Delivery address is required.'),
            ]);
        }

        $deliveryFeePerChef = (float) config('shop.delivery_fee', 3.00);

        // Group items by chef
        $chefGroups = [];
        foreach ($cart as $mealId => $qty) {
            if (! isset($meals[$mealId])) {
                continue;
            }

            $quantity = is_array($qty) ? (int) ($qty['quantity'] ?? 1) : (int) $qty;
            $meal = $meals[$mealId];
            $chefGroups[$meal->chef_id][] = [
                'meal' => $meal,
                'quantity' => $quantity,
                'price' => (float) $meal->price,
            ];
        }

        $createdOrders = collect();

        DB::beginTransaction();
        try {
            foreach ($chefGroups as $chefId => $items) {
                $subtotal = collect($items)->sum(fn ($i) => $i['price'] * $i['quantity']);
                $total = $subtotal + $deliveryFeePerChef;

                $order = Order::create([
                    'user_id' => $customer->id,
                    'chef_id' => $chefId,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFeePerChef,
                    'total' => $total,
                    'address' => $addressText,
                    'phone' => $validated['phone'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'pending',
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'meal_id' => $item['meal']->id,
                        'meal_name' => $item['meal']->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                // Send queued notification to chef
                $chef = User::find($chefId);
                if ($chef) {
                    try {
                        $chef->notify(new NewOrderNotification($order));
                    } catch (\Throwable $e) {
                        report($e);
                    }
                }

                $createdOrders->push($order);
            }

            DB::commit();
            $this->cartService->clear();

            return $createdOrders;
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }
}
