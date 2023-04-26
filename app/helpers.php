<?php

use Illuminate\Http\UploadedFile;

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
     * @param float|null $amount
     * @param null $currency
     * @return string
     */
    function f_currency(?float $amount, $currency = null): string
    {
        return StringFormatter::formatCurrency($amount, $currency);
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

if (!function_exists('store_file')) {
    /**
     * Store an uploaded file publicly, and delete the old one if provided
     * @param UploadedFile $file
     * @param string|null $old The storage location of the old file (if it needs deleting)
     * @return string The location of the stored file
     */
    function store_file(UploadedFile $file, string $old = null): string
    {
        $path = $file->storePublicly('uploads/images');
        isset($old) && Storage::delete($old);
        return $path;
    }
}
if (!function_exists('random_colors')) {
    /**
     * Generate a random set of distinct colors
     */
    function random_colors(int $count = 1): array
    {
        if ($count < 1) $count = 1;
        $colors = [];
        for ($x = 0; $x < $count; $x++) {
            $colors[] = "hsl(" . ($x * (360 / $count) % 360) . ",75%,50%)";
        }
        return $colors;
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
