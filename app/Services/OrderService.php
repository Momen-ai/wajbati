<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Compute a live quote for a cart array [meal_id => quantity].
     * Single source of truth for both cart display and checkout creation.
     */
    public function quote(array $cart): Quote
    {
        if (empty($cart)) {
            return new Quote(collect(), 0.0, 0.0, 0.0, 0);
        }

        $mealIds = array_keys($cart);
        $meals = Meal::with(['chef', 'image'])->whereIn('id', $mealIds)->get()->keyBy('id');

        if ($meals->isEmpty()) {
            return new Quote(collect(), 0.0, 0.0, 0.0, 0);
        }

        $deliveryFeePerChef = (float) config('shop.delivery_fee', 3.00);
        $byChef = [];
        $overallSubtotal = 0.0;
        $totalItemCount = 0;

        foreach ($cart as $mealId => $qty) {
            if (! isset($meals[$mealId])) {
                continue;
            }

            $quantity = is_array($qty) ? (int) ($qty['quantity'] ?? 1) : (int) $qty;
            if ($quantity <= 0) {
                continue;
            }

            $meal = $meals[$mealId];
            $chefId = $meal->chef_id;
            $linePrice = (float) $meal->price;
            $lineTotal = $linePrice * $quantity;

            $overallSubtotal += $lineTotal;
            $totalItemCount += $quantity;

            if (! isset($byChef[$chefId])) {
                $byChef[$chefId] = [
                    'chef' => $meal->chef,
                    'chef_id' => $chefId,
                    'items' => [],
                    'subtotal' => 0.0,
                    'delivery_fee' => $deliveryFeePerChef,
                    'total' => 0.0,
                ];
            }

            $byChef[$chefId]['items'][] = [
                'meal' => $meal,
                'quantity' => $quantity,
                'price' => $linePrice,
                'line_total' => $lineTotal,
            ];

            $byChef[$chefId]['subtotal'] += $lineTotal;
            $byChef[$chefId]['total'] = $byChef[$chefId]['subtotal'] + $deliveryFeePerChef;
        }

        $totalDeliveryFee = count($byChef) * $deliveryFeePerChef;
        $grandTotal = $overallSubtotal + $totalDeliveryFee;

        return new Quote(
            byChef: collect($byChef),
            subtotal: $overallSubtotal,
            deliveryFee: $totalDeliveryFee,
            grandTotal: $grandTotal,
            itemCount: $totalItemCount
        );
    }

    /**
     * Create orders from the current shopping cart.
     *
     * @throws ValidationException
     */
    public function createOrdersFromCart(User $customer, array $validated): Collection
    {
        $lock = Cache::lock('checkout_user_'.$customer->id, 10);
        if (! $lock->get()) {
            throw ValidationException::withMessages([
                'cart' => __('An order is already being processed. Please wait.'),
            ]);
        }

        try {
            $cart = $this->cartService->get();
            if (empty($cart)) {
                throw ValidationException::withMessages([
                    'cart' => __('Your cart is empty.'),
                ]);
            }

            $quote = $this->quote($cart);
            if ($quote->isEmpty()) {
                $this->cartService->clear();
                throw ValidationException::withMessages([
                    'cart' => __('None of the items in your cart are currently available.'),
                ]);
            }

            // Reject checkout if any meal in cart is unavailable
            foreach ($quote->byChef as $chefGroup) {
                foreach ($chefGroup['items'] as $item) {
                    if (! $item['meal']->is_available) {
                        throw ValidationException::withMessages([
                            'cart' => __('The meal ":name" is currently unavailable.', ['name' => $item['meal']->name]),
                        ]);
                    }
                }
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

            $createdOrders = collect();

            DB::beginTransaction();
            try {
                foreach ($quote->byChef as $chefId => $chefGroup) {
                    $order = Order::create([
                        'user_id' => $customer->id,
                        'chef_id' => $chefId,
                        'subtotal' => $chefGroup['subtotal'],
                        'delivery_fee' => $chefGroup['delivery_fee'],
                        'total' => $chefGroup['total'],
                        'address' => $addressText,
                        'phone' => $validated['phone'],
                        'payment_method' => $validated['payment_method'],
                        'payment_status' => 'pending',
                        'status' => 'pending',
                    ]);

                    foreach ($chefGroup['items'] as $item) {
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
        } finally {
            optional($lock)->release();
        }
    }
}
