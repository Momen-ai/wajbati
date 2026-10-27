<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.meal', 'chef'])
            ->latest()
            ->paginate(10);

        return view('front.orders.index', compact('orders'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $this->orderService->createOrdersFromCart(
                $request->user(),
                $request->validated()
            );

            return redirect()->route('orders.index')
                ->with('success', __('Thank you! Your order has been placed successfully.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', __('Something went wrong while placing your order. Please try again.'));
        }
    }

    public function show(int $id): View
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.meal', 'chef'])
            ->findOrFail($id);

        return view('front.orders.show', compact('order'));
    }

    /**
     * Customer can cancel their order if still pending.
     */
    public function cancel(int $id): RedirectResponse
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', __('Only pending orders can be cancelled.'));
        }

        $order->update(['status' => 'cancelled']);

        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'cancelled',
            'comment' => 'Cancelled by customer',
        ]);

        return back()->with('success', __('Order cancelled successfully.'));
    }

    /**
     * Customer can reorder items from a previous order into cart.
     */
    public function reorder(int $id, \App\Services\CartService $cartService): RedirectResponse
    {
        $order = Order::where('user_id', Auth::id())
            ->with('items.meal')
            ->findOrFail($id);

        $addedCount = 0;
        foreach ($order->items as $item) {
            if ($item->meal && $item->meal->is_available) {
                $cartService->add($item->meal->id, $item->quantity);
                $addedCount++;
            }
        }

        if ($addedCount === 0) {
            return back()->with('error', __('None of the items from this order are currently available.'));
        }

        return redirect()->route('cart.index')->with('success', __('Items added to your cart.'));
    }
}
