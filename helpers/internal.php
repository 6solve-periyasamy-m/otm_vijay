<?php

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

if (!function_exists('sigfig')) {
    function sigfig($number, $figures = 2, bool $floor = false): float
    {
        if ($floor) {
            return floor(($number * (10**$figures)))/(10**$figures);
        }
        return ceil(($number * (10**$figures)))/(10**$figures);
    }
}
if (!function_exists('round_to_five')) {
    /**
     *  Rounds to the nearest 5 or 0 in final column
     *
     * @param int|float|null $number
     * @return float
     */
    function round_to_five(int|float|null $number): float
    {
        $number = sigfig($number ?? 0, 0);
        if ($number % 5 === 0) {
            return $number;
        }
        return ($number + (5 - $number % 5));
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
            throw new Exception($message);
        } catch (Exception) {
            Log::info($message);
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
            throw new Exception($message);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }
}
if (!function_exists('diff_in_nights')) {
    /**
     * @param Carbon $start Start date
     * @param Carbon $end End date
     * @return string
     */
    function diff_in_nights(Carbon $start, Carbon $end): string
    {
        return $start->setTime(0,0)->diff($end)->format('%a');
    }
}
if (!function_exists('strip_non_alphanumeric')) {
    /**
     * @param string $string
     * @return string
     */
    function strip_non_alphanumeric(string $string): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $string);
    }
}
if (!function_exists('add_email_alias')) {
    function add_email_alias(string $email, string $alias): string
    {
        [$localPart, $domainPart] = explode('@', $email);

        $newLocalPart = $localPart . '+'. $alias;

        return $newLocalPart . '@' . $domainPart;
    }
}
if (!function_exists('round_to_nearest')) {
    /**
     * Round up to nearest X
     *
     * @param float|int|null $number
     * @param float|null $value
     * @return float
     */
    function round_to_nearest(float|int|null $number, float|null $value = null): float
    {
        if ($number === null) { return 0.0; }
        if (empty($value)) { return $number; }
        return sigfig(ceil($number / $value) * $value);
    }
}
if (!function_exists('get_date')) {
    /**
     * Get date from formats
     * @param string $date The date to get from the formats
     * @param string[] $formats List of valid/accepted formats
     * @return Carbon|null
     */
    function get_date(string $date, string ...$formats): Carbon|null
    {
        // Standard array of formats to test
        $standard = ['Y-m-d', 'Y-m-d H:i:s', 'd/m/Y H:i', 'd-m-Y H:i'];
        foreach ([...$formats, ...$standard] as $format) {
            try {
                $carbon = Carbon::createFromFormat($format, $date);
                if ($carbon instanceof Carbon) {
                    return $carbon;
                }
            } catch (InvalidFormatException) {
                continue;
            }
        }
        return null;
    }
}
if (!function_exists('div_id')) {
    /**
     * Returns an ID suitable for a DIV, sanitzed to be compatible as a JS Variable Name
     *
     * @return string
     */
    function div_id(): string
    {
        return preg_replace("/\d/u", "", Str::random());
    }
}