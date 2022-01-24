<?php

namespace App\Repository\Facades;

use App\Repository\SettingsRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\App;
use NumberFormatter;

class StringFormatter
{
    public function formatCurrency($value, $currency = null) : string {
        if (!isset($currency)) {
            $currency = SettingsRepository::getOrDefault('system.currency', 'GBP');
        }
        return (new \NumberFormatter(App::currentLocale(), NumberFormatter::CURRENCY))->formatCurrency($value, $currency);
    }

    public function formatDate($date) : string {
        $format = SettingsRepository::getOrDefault('system.format.date', 'd/m/Y');
        try {
            return Carbon::parse($date)->format($format);
        } catch (InvalidFormatException $exception) {
            return $date;
        }
    }

    public function formatDateTime($date) : string {
        $format = SettingsRepository::getOrDefault('system.format.date', 'd/m/Y') . ' ' . SettingsRepository::getOrDefault('system.format.time', 'H:i');
        try {
            return Carbon::parse($date)->format($format);
        } catch (InvalidFormatException $exception) {
            return $date;
        }
    }

    public function formatBoolean($boolean) : string {
        return $boolean ? 'Yes' : 'No';
    }
}
