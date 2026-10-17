<?php

namespace App\Services;

use App\Models\Order;

class OrderStats
{
    /**
     * Compute total verified revenue: paid orders in delivered or completed status.
     */
    public static function totalRevenue(?int $chefId = null): float
    {
        $query = Order::where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'completed']);

        if ($chefId !== null) {
            $query->where('chef_id', $chefId);
        }

        return (float) $query->sum('total');
    }
}
