<?php

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

if (!function_exists('sigfig')) {
    function sigfig($number, $figures = 2): float
    {
        return ceil(($number * (10**$figures)))/(10**$figures);
    }
}
if (!function_exists('days_until')) {
    /**
     * Calculates the number of days until/since now. Returns negative if in the past
     * @param Carbon|null $date The date to check. Returns null if null
     * @return int|null The number of days, or null if null is passed
     */
    function days_until(Carbon|null $date): int|null
    {
        if (empty($date)) return null;
        return $date->isBefore(Carbon::now()) ? ($date->diffInDays(Carbon::now())) * -1 : ($date->diffInDays(Carbon::now()));
    }
}
if (!function_exists('generify_date')) {
    /**
     * Takes a date in an unknown format, and attempts to convert it to Y-m-d. Returns null if invalid
     * @param string|null $date
     * @return string|null
     */
    function generify_date(string|null $date): string|null
    {
        if ($date === null) return null;
        try {
            $carbon = Carbon::createFromFormat('Y-m-d', $date);
            return $carbon->format('Y-m-d');
        } catch (InvalidFormatException) {}
        try {
            $carbon = Carbon::createFromFormat('d/m/Y', $date);
            return $carbon->format('Y-m-d');
        } catch (InvalidFormatException) {}
        try {
            $carbon = Carbon::createFromFormat('m/d/Y', $date);
            return $carbon->format('Y-m-d');
        } catch (InvalidFormatException) {}
        return null;
    }
}
if (!function_exists('stack_dump')) {
    /**
     * Dump the current stack trace to the log file, optionally with a message.
     * Used for debugging
     * @param string|null $message
     * @return void
     */
    function stack_dump(string|null $message = null): void
    {
        try {
            throw new \Exception($message);
        } catch (\Exception) {
            \Log::info($message);
        }
    }
}
if (!function_exists('str_to_map')) {
    /**
     * Convert a plaintext map to keyed array
     * @param string $message
     * @param string $separator The marker for equivalence (default =)
     * @param string $linefeed The marker for line change (default CR/LF/CRLF)
     * @param string $merge Character to put back into merge (default =)
     * @return array
     */
    function str_to_map(string $message, string $separator = '/[=]/', string $linefeed = "/[\r\n]+/", string $merge = '='): array
    {
        $data = [];
        $lines = preg_split($linefeed, $message);
        foreach ($lines as $line) {
            $split = preg_split($separator, $line);
            $data[$split[0]] = implode($merge, array_slice($split, 1));
        }
        return $data;
    }
}
if (!function_exists('debug_stack')) {
    /**
     * Print the current stack trace to the Log file under debug
     * @param string $message Message to be printed with the stack trace
     * @return void
     */
    function debug_stack(string $message = "Stack Dumped"): void
    {
        try {
            throw new \Exception($message);
        } catch (\Exception $e) {
            \Log::debug($e);
        }
    }
}
