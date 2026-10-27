<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Configuration
    |--------------------------------------------------------------------------
    */

    'currency' => [
        'code' => env('SHOP_CURRENCY_CODE', 'ILS'),
        'symbol' => env('SHOP_CURRENCY_SYMBOL', '₪'),
    ],

    // Fixed delivery fee per chef sub-order
    'delivery_fee' => (float) env('SHOP_DELIVERY_FEE', 3.00),

    // Card payment gateway toggle (false until real gateway integrated)
    'card_enabled' => (bool) env('SHOP_CARD_ENABLED', false),

    // Maximum quantity allowed per individual cart line
    'max_qty_per_item' => (int) env('SHOP_MAX_QTY_PER_ITEM', 20),

    // Minimum order amount
    'min_order_amount' => (float) env('SHOP_MIN_ORDER_AMOUNT', 0.00),
];
