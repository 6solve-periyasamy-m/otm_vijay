<?php

namespace App\Repository\Facades;

use App\Repository\SettingsRepository;
use Illuminate\Support\Facades\App;
use NumberFormatter;

class Currency
{
    public function format($value, $currency = null) {
        if (!isset($currency)) {
            $currency = SettingsRepository::getOrDefault('system.currency', 'GBP');
        }
        return (new \NumberFormatter(App::currentLocale(), NumberFormatter::CURRENCY))->formatCurrency($value, $currency);
    }
}
