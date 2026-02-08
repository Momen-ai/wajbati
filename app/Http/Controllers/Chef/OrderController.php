<?php

namespace App\Http\Controllers\Chef;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderStatusUpdatedNotification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('chef_id', Auth::id())
            ->with(['user', 'items.meal'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('front.chef.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->chef_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,preparing,prepared,delivered,completed,rejected,cancelled',
        ]);

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        $invalidTransitions = [
            'cancelled' => ['accepted', 'preparing', 'prepared', 'delivered', 'completed'],
            'completed' => ['pending', 'accepted', 'preparing', 'prepared', 'delivered', 'rejected', 'cancelled'],
            'delivered' => ['pending', 'accepted', 'preparing', 'prepared', 'rejected'],
        ];

        if (isset($invalidTransitions[$currentStatus]) && in_array($newStatus, $invalidTransitions[$currentStatus])) {
            return back()->with('error', "Cannot change status from {$currentStatus} to {$newStatus}.");
        }

        $order->update(['status' => $newStatus]);

        // Trigger Notification for the User
        $order->user->notify(new OrderStatusUpdatedNotification($order));

        return back()->with('success', 'Order status updated successfully.');
    }

    public function show(Order $order)
    {
        if ($order->chef_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['user', 'items.meal']);
        return view('front.chef.orders.show', compact('order'));
    }
}
