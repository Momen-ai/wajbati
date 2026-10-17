<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'chef'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $chefs = User::where('role', 'chef')->get();
        $meals = Meal::all();

        return view('admin.orders.create', compact('users', 'chefs', 'meals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'chef_id' => 'required|exists:users,id',
            'address' => 'nullable|string',
            'status' => 'required',
            'items' => 'required|array',
            'items.*.meal_id' => 'required|exists:meals,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $meal = Meal::find($item['meal_id']);
            $total += $meal->price * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => $request->user_id,
            'chef_id' => $request->chef_id,
            'total' => $total,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        foreach ($request->items as $item) {
            $meal = Meal::find($item['meal_id']);

            $order->items()->create([
                'meal_id' => $meal->id,
                'quantity' => $item['quantity'],
                'price' => $meal->price,
            ]);
        }

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', 'Order created successfully');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'chef', 'items.meal']);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::where('role', 'user')->get();
        $chefs = User::where('role', 'chef')->get();

        return view('admin.orders.edit', compact('order', 'users', 'chefs'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected,preparing,prepared,delivered,completed,cancelled',
            'address' => 'nullable|string',
            'force' => 'nullable|boolean',
            'note' => 'nullable|string|max:500',
        ]);

        $fromStatus = $order->status;
        $toStatus = $validated['status'];
        $forced = $request->boolean('force');

        if ($fromStatus !== $toStatus) {
            if (! $forced && ! \App\Services\OrderStatusService::canTransitionTo($fromStatus, $toStatus)) {
                $allowed = implode(', ', \App\Services\OrderStatusService::getAllowedStatuses($fromStatus));

                return back()->with('error', __(
                    'Invalid status transition from :from to :to. Allowed: :allowed (check "Force" to override).',
                    ['from' => $fromStatus, 'to' => $toStatus, 'allowed' => $allowed ?: 'None']
                ));
            }

            \App\Models\OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'user_id' => auth()->id(),
                'note' => $request->input('note'),
                'forced' => $forced,
            ]);

            app(\App\Services\OrderPaymentService::class)->handleStatusTransition($order, $toStatus);

            if ($order->user) {
                try {
                    $order->user->notify(new \App\Notifications\OrderStatusUpdatedNotification($order));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        $order->update([
            'status' => $toStatus,
            'address' => $validated['address'] ?? $order->address,
        ]);

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', __('Order updated successfully.'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', 'Order deleted successfully');
    }
}

//
