<?php

use Carbon\Carbon;

if (!function_exists('sigfig')) {
    function sigfig($number, $figures = 2): float
    {
        return ceil(($number * (10**$figures)))/(10**$figures);
    }
}

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

if (!function_exists('f_currency')) {
    /**
     * Alias for StringFormatter::formatCurrency
     * @param float $amount
     * @param $currency
     * @return string
     */
    function f_currency(float $amount, $currency = null): string
    {
        return StringFormatter::formatCurrency($amount, $currency);
    }
}

if (!function_exists('f_date')) {
    /**
     * Alias for StringFormatter::formatDate
     * @param Carbon $date
     * @return string
     */
    function f_date(Carbon $date): string
    {
        return StringFormatter::formatDate($date);
    }
}

if (!function_exists('f_datetime')) {
    /**
     * Alias for StringFormatter::formatDateTime
     * @param Carbon $date
     * @return string
     */
    function f_datetime(Carbon $date): string
    {
        return StringFormatter::formatDateTime($date);
    }
}