<?php

namespace App\Repository\Facades;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\App;
use NumberFormatter;

class StringFormatter
{
    public function formatCurrency($value, $currency = null) : string {
        if (!isset($currency)) {
            $currency = setting('system.currency', 'GBP');
        }
        return (new NumberFormatter(App::currentLocale(), NumberFormatter::CURRENCY))->formatCurrency($value ?? 0, $currency);
    }

    public function formatDate($date) : string {
        if (empty($date)) return "";
        $format = setting('system.format.date', 'd/m/Y');
        try {
            return Carbon::parse($date)->format($format);
        } catch (InvalidFormatException $exception) {
            return $date;
        }
    }

    public function formatTime($date): string {
        if (empty($date)) return "";
        $format = setting('system.format.time', 'H:i');
        try {
            return Carbon::parse($date)->format($format);
        } catch (InvalidFormatException $exception) {
            return $date;
        }
    }

    public function formatDateTime($date) : string {
        if (empty($date)) return "";
        return $this->formatDate($date) . ' ' . $this->formatTime($date);
    }

    public function formatBoolean($boolean) : string {
        return $boolean ? 'Yes' : 'No';
    }
}
