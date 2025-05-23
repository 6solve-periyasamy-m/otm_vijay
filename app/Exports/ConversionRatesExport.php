<?php

namespace App\Exports;

use App\Repository\SettingsRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ConversionRatesExport implements FromView
{
    public function view(): View
    {
        return view('partials.admin.system.exports.conversion-rates', ['data' => SettingsRepository::getConversionRates(),]);
    }
}
