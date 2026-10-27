<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGateway
{
    /**
     * Process payment for an order.
     */
    public function process(Order $order): array;
}
