<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderMerchandiseExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.merchandise', ['data' => ReportRepository::getOrderMerchandiseReport(),]);
    }
}
