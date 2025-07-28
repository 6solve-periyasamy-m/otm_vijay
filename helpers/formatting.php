<?php

use App\Models\Location\Currency;

if (!function_exists('f_currency')) {
    /**
     * Alias for StringFormatter::formatCurrency.
     *
     * Formats a number as a specific currency, performing conversion where required, and displaying both the original and converted values
     *
     * ex. $100 (£75)
     *
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
if (!function_exists('fr_currency')) {
    /**
     * Format a number as a specific currency. Does no conversion, just outputs a string
     *
     * ex. $100
     *
     * @param float|null $amount The amount to be formatted
     * @param Currency|string|null $currency The currency to format in
     * @return string
     */
    function fr_currency(?float $amount, Currency|string|null $currency = null, bool $strip = false, ?int $decimalPrecision = 2): string
    {
        if (!is_string($currency)) $currency = ($currency ?? Settings::currency())?->code;
        //$code = flag('currency.code.show', false) ? "{$currency} " : "";
        //return $code . (new NumberFormatter(App::currentLocale(), NumberFormatter::CURRENCY))->formatCurrency($amount ?? 0.0, $currency);
        $roundedAmount = round($amount ?? 0.0); // Round to nearest whole number
        $formatter = new NumberFormatter(App::currentLocale(), NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $decimalPrecision ?? 0); // Hide decimals
        $amountToFormat = $decimalPrecision ? $amount : $roundedAmount;
        $formatted = $formatter->formatCurrency($amountToFormat, $currency);
        $code = flag('currency.code.show', false) ? "{$currency} " : "";
        return $code . $formatted;
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
if (!function_exists('sanitize')) {
    /**
     * Sanitize a string for a filename
     *
     * @param string|null $str
     * @return string
     */
    function sanitize(?string $str): string
    {
        $str = str_replace(' ', '-', strtolower($str));
        return preg_replace('/[^A-Za-z0-9\-]/', '', $str);
    }
}
