<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;

class CashOnDeliveryGateway implements PaymentGateway
{
    public function process(Order $order): array
    {
        return [
            'success' => true,
            'status' => 'pending',
            'message' => 'Cash on delivery order initialized.',
        ];
    }
}
