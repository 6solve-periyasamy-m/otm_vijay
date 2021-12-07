<?php

namespace App\Repository\Facades;

use App\Repository\SettingsRepository;
use Carbon\Carbon;
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
        return Carbon::parse($date)->format($format);
    }

    public function formatDateTime($date) : string {
        $format = SettingsRepository::getOrDefault('system.format.date', 'd/m/Y') . ' ' . SettingsRepository::getOrDefault('system.format.time', 'H:i');
        return Carbon::parse($date)->format($format);
    }
}
