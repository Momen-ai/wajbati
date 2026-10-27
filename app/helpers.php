<?php

if (! function_exists('money')) {
    /**
     * Format a monetary amount using the configured shop currency.
     */
    function money(float|int|string|null $amount): string
    {
        $numeric = (float) ($amount ?? 0);
        $formatted = number_format($numeric, 2);
        $currencyConfig = config('shop.currency');
        $symbol = is_array($currencyConfig) ? ($currencyConfig['symbol'] ?? '₪') : (string) ($currencyConfig ?: '₪');

        return app()->getLocale() === 'ar'
            ? "{$formatted} {$symbol}"
            : "{$symbol} {$formatted}";
    }
}
