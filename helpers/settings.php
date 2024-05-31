<?php

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
