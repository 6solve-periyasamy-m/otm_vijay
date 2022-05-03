<?php

if (!function_exists('sigfig')) {
    function sigfig($number, $figures = 2): float
    {
        return ceil(($number * (10**$figures)))/(10**$figures);
    }
}
