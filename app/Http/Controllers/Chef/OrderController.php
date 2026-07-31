<?php

namespace App\Http\Controllers\Chef;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Services\OrderStatusService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('chef_id', auth()->id())
            ->with(['user', 'items.meal'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('front.chef.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        Gate::authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(OrderStatusService::TRANSITIONS)),
        ]);

        $currentStatus = $order->status;
        $newStatus     = $validated['status'];

        if (! OrderStatusService::canTransitionTo($currentStatus, $newStatus)) {
            $allowed = implode(', ', OrderStatusService::getAllowedStatuses($currentStatus));
            $message = $allowed
                ? "Cannot change status from '{$currentStatus}' to '{$newStatus}'. Allowed next statuses: {$allowed}."
                : "Order status '{$currentStatus}' is terminal and cannot be changed.";

            return back()->with('error', $message);
        }

        $order->update(['status' => $newStatus]);

        // Notify the customer about the status change
        $order->user->notify(new OrderStatusUpdatedNotification($order));

        return back()->with('success', 'Order status updated successfully.');
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['user', 'items.meal']);
        return view('front.chef.orders.show', compact('order'));
    }
}
