<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Meal;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with(['items.meal', 'chef'])->latest()->paginate(10);
        return view('front.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'address' => 'required_without:address_id|string|max:255',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cash,card',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $address = $request->address;
        if ($request->address_id) {
            $savedAddress = \App\Models\Address::find($request->address_id);
            if ($savedAddress && $savedAddress->user_id == Auth::id()) {
                $address = $savedAddress->address . ($savedAddress->city ? ', ' . $savedAddress->city : '');
            }
        }

        $chefOrders = [];
        foreach ($cart as $id => $details) {
            $meal = Meal::find($id);
            if ($meal) {
                $chefOrders[$meal->chef_id][] = [
                    'meal_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ];
            }
        }

        DB::beginTransaction();
        try {
            foreach ($chefOrders as $chefId => $items) {
                $total = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'chef_id' => $chefId,
                    'total' => $total + 3, // + fixed delivery fee
                    'address' => $address,
                    'phone' => $request->phone,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_method == 'card' ? 'paid' : 'pending',
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'meal_id' => $item['meal_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                // Notify Chef
                $chef = User::find($chefId);
                $chef->notify(new NewOrderNotification($order));
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->route('orders.index')->with('success', 'Thank you! Your order has been placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while placing your order: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())->with(['items.meal', 'chef'])->findOrFail($id);
        return view('front.orders.show', compact('order'));
    }
}
