<?php

use Carbon\Carbon;

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

if (!function_exists('set_normalize_date')) {
    function set_normalize_date($item, $date_key) {
        $item->normalize_date = Carbon::parse(data_get($item->details, $date_key))->format('Y-m-d');
        return $item;
    }
}
