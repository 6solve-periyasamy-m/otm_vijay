<?php

namespace App\Repository\Facades;

use App\Models\Location\Currency;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\App;
use NumberFormatter;

class StringFormatter
{
    public function formatCurrency($value, $currentCurrency = null, $conversion = null, $toCurrency = null) : string {
        $systemCurrency = Currency::code(setting('system.currency', 'GBP'));
        if (is_string($currentCurrency)) { $currentCurrency = Currency::code($currentCurrency); }
        if (is_string($toCurrency)) { $toCurrency = Currency::code($toCurrency); }
        $currentCurrency = $currentCurrency ?? $systemCurrency;
        $toCurrency = $toCurrency ?? $systemCurrency;
        if ($currentCurrency !== $toCurrency) {
            $rate = $conversion ?? \Settings::getConversionRate($currentCurrency, $toCurrency);
            if ($rate !== null && $rate != 1) {
                return $this->currency($value, $currentCurrency) . " (" . $this->currency(sigfig($value * $rate), $toCurrency) . ")";
            }
        }
        return $this->currency($value, $currentCurrency);
    }

    private function currency($value, $currency): string
    {
        if (!is_string($currency)) $currency = $currency->code;
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
