<?php

use App\Models\Location\Currency;

if (!function_exists('f_currency')) {
    /**
     * Alias for StringFormatter::formatCurrency
     * @param float|null $amount
     * @param Currency|string|null $currency
     * @param float|null $conversion
     * @param Currency|string|null $toCurrency
     * @return string
     */
    function f_currency(?float $amount, Currency|string|null $currency = null, ?float $conversion = null, Currency|string|null $toCurrency = null): string
    {
        return StringFormatter::formatCurrency($amount, $currency, $conversion, $toCurrency);
    }
}
if (!function_exists('f_date')) {
    /**
     * Alias for StringFormatter::formatDate
     * @param $date
     * @return string
     */
    function f_date($date): string
    {
        return StringFormatter::formatDate($date);
    }
}
if (!function_exists('f_datetime')) {
    /**
     * Alias for StringFormatter::formatDateTime
     * @param $date
     * @return string
     */
    function f_datetime($date): string
    {
        return StringFormatter::formatDateTime($date);
    }
}
if (!function_exists('f_time')) {
    /**
     * Alias for StringFormatter::formatTime
     * @param $date
     * @return string
     */
    function f_time($date): string
    {
        return StringFormatter::formatTime($date);
    }
}
if (!function_exists('f_bool')) {
    /**
     * Alias for StringFormatter::formatBoolean
     * @param bool|null $value
     * @return string
     */
    function f_bool(?bool $value): string
    {
        return StringFormatter::formatBoolean($value);
    }
}
if (!function_exists('nbsp')) {
    /**
     * Converts spaces into Non-breaking spaces for rendering
     * @param string $str
     * @return string
     */
    function nbsp(string $str): string
    {
        return str_replace(' ', '&nbsp;', $str);
    }
}
if (!function_exists('camel_to_text')) {
    /**
     * Convert camel case text to regular strings
     * @param string $text
     * @return string
     */
    function camel_to_text(string $text): string
    {
        $arr = preg_split('/([A-Z]+[^A-Z]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        return implode(' ', $arr);
    }
}
if (!function_exists('snake_to_pascal')) {
    /**
     * Converts a camel case string to pascal case
     * @param string $str
     * @return string The Pascal String
     */
    function snake_to_pascal(string $str): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $str)));
    }
}
if (!function_exists('truncate')) {
    /**
     * Truncate a string to a certain length, and append ellipsis to the end
     * @param string|null $str The string to truncate
     * @param int $chars The number of characters to truncate to (default: 150)
     * @param string $append The string to append to the end (default: ...)
     * @return string The truncated string
     */
    function truncate(?string $str, int $chars = 150, string $append = '...'): string
    {
        return Str::limit($str ?? "", $chars, $append);
    }
}
