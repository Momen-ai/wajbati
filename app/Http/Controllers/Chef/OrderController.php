<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
            'status' => 'required|in:'.implode(',', array_keys(OrderStatusService::TRANSITIONS)),
        ]);

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        if (! OrderStatusService::canTransitionTo($currentStatus, $newStatus)) {
            $allowed = implode(', ', OrderStatusService::getAllowedStatuses($currentStatus));
            $message = $allowed
                ? "Cannot change status from '{$currentStatus}' to '{$newStatus}'. Allowed next statuses: {$allowed}."
                : "Order status '{$currentStatus}' is terminal and cannot be changed.";

            return back()->with('error', $message);
        }

        $fromStatus = $order->status;
        $order->update(['status' => $newStatus]);

        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $fromStatus,
            'to_status' => $newStatus,
            'user_id' => auth()->id(),
            'forced' => false,
        ]);

        // Update payment status lifecycle (COD paid on delivery, etc.)
        app(\App\Services\OrderPaymentService::class)->handleStatusTransition($order, $newStatus);

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
