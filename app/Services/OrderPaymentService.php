<?php

namespace App\Services;

use App\Models\Order;

class OrderPaymentService
{
    /**
     * Handle payment status transition when order status changes.
     */
    public function handleStatusTransition(Order $order, string $newStatus): void
    {
        // When cash order is delivered or completed -> mark paid
        if (in_array($newStatus, ['delivered', 'completed'], true)) {
            if ($order->payment_method === 'cash' || $order->payment_status === 'pending') {
                $order->payment_status = 'paid';
            }
        }

        // When order is cancelled or rejected
        if (in_array($newStatus, ['cancelled', 'rejected'], true)) {
            if ($order->payment_status === 'paid') {
                $order->payment_status = 'refund_pending';
            } else {
                $order->payment_status = 'failed';
            }
        }

        $order->save();
    }
}
