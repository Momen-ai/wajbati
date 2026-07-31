<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the order.
     * Admins can view any order; chefs can only view orders assigned to them.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $order->chef_id;
    }

    /**
     * Determine whether the user can update the order status.
     * Only the chef assigned to the order may update its status.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->chef_id;
    }
}
