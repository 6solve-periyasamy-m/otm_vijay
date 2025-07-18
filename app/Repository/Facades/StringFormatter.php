<?php

namespace App\Repository\Facades;

use App\Models\Location\Currency;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class StringFormatter
{
    public function formatCurrency($value, $currentCurrency = null, $conversion = null, $toCurrency = null) : string {
        $systemCurrency = Currency::fromCode(setting('system.currency', 'GBP'));
        if (is_string($currentCurrency)) { $currentCurrency = Currency::fromCode($currentCurrency); }
        if (is_string($toCurrency)) { $toCurrency = Currency::fromCode($toCurrency); }
        $currentCurrency = $currentCurrency ?? $systemCurrency;
        $toCurrency = $toCurrency ?? $systemCurrency;
        if ($currentCurrency !== $toCurrency) {
            $rate = $conversion ?? \Settings::getConversionRate($currentCurrency, $toCurrency);
            if ($rate !== null && $rate != 1) {
                return $this->currency(sigfig($value * $rate), $toCurrency)  . " (" . $this->currency($value, $currentCurrency) . ")";
            }
        }
        return $this->currency($value, $currentCurrency);
    }

    private function currency($value, $currency): string
    {
        return fr_currency($value, $currency);
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
