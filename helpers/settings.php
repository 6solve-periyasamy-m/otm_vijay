<?php

use App\Models\Location\Currency;

if (!function_exists('setting')) {
    /**
     * Alias for fetching a setting key
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    function setting(string $key, ?string $default = null): ?string
    {
        return Settings::get($key, $default);
    }
}
if (!function_exists('flag')) {
    /**
     * Alias for feting a boolean setting key
     * @param string $key
     * @param bool $default
     * @return bool
     */
    function flag(string $key, bool $default = false): bool
    {
        return Settings::getBoolean($key, $default);
    }
}
if (!function_exists('fx_convert')) {
    /**
     * @param float|int|null $value The value to convert
     * @param Currency|string|null $from The currency it is in. can leave null if providing rate
     * @param Currency|string|null $to The currency to convert to (defaults to system)
     * @param float|null $rate The conversion rate (will lookup if null)
     * @return float|null The converted amount
     */
    function fx_convert(float|int|null $value, Currency|string|null $from = null, Currency|string|null $to = null, float|null $rate = null): float|null
    {
        if ($value === null) { return null; }
        if ($from === null) { return $value * ($rate ?? 1.0); }
        if ($rate === null) {
            $systemCurrency = Settings::currency();
            if (is_string($from)) { $from = Currency::fromCode($from); }
            if (is_string($to)) { $to = Currency::fromCode($to); }
            $to = $to ?? $systemCurrency;
            $rate = Settings::getConversionRate($from, $to) ?? 1.0;
        }
        return fx_rate($value, $rate);
    }
}
if (!function_exists('fx_rate')) {
    /**
     * @param float $value
     * @param float|null $rate
     * @return float
     */
    function fx_rate(float $value, float|null $rate): float
    {
        $rate = $rate ?? 1.0;
        return sigfig($value * $rate);
    }
}

if (!function_exists('default_customer_fields')) {
    function default_customer_fields(): array
    {
        return ['email', 'mobile_number', 'internal_notes'];
    }
}

if (!function_exists('default_order_customer_fields')) {
    function default_order_customer_fields(): array
    {
        return ['first_name', 'last_name', 'email', 'date_of_birth'];
    }
}