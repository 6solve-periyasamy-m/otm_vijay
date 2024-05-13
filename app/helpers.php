<?php

use App\Models\Location\Currency;
use App\Models\User;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
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
     * @param Currency|string|null $currency
     * @param float|null $conversion
     * @param string|null $toCurrency
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
if (!function_exists('is_otm')) {
    function is_otm(): bool
    {
        $user = get_current_admin();
        if (empty($user) || !($user instanceof User)) return false;
        return $user->getHighestRoleLevel() >= 999;
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
if (!function_exists('img_to_b64')) {
    function img_to_b64(string $file, string $prefix = "data:image/png;base64,"): string
    {
        return $prefix.base64_encode(file_get_contents(public_path($file)));
    }
}
if (!function_exists('svg_to_b64')) {
    function svg_to_b64(string $file): string
    {
        return img_to_b64($file, "data:image/svg+xml;base64,");
    }
}
if (!function_exists('generate_qr')) {
    /**
     * Generate a Base64 QR code for a given content
     * @param string $content
     * @param string $prefix Prefix to use for the QR code (defaults to base64 PNG for img tags)
     * @return string base64 representation of the QR code
     */
    function generate_qr(string $content, string $prefix = "data:image/png;base64,"): string
    {
        return $prefix . base64_encode((new Writer(
            new ImageRenderer(
                new RendererStyle(400),
                new ImagickImageBackEnd(),
            )))->writeString($content));
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
if (!function_exists('get_current_admin')) {
    /**
     * Get the currently logged in admin user, or null
     * @return User|null
     */
    function get_current_admin(): User|null
    {
        $user = Auth::guard('web')->user();
        if ($user instanceof User) return $user;
        return null;
    }
}
