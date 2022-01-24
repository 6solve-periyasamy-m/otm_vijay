<?php

namespace App\Exports;

use App\Repository\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.orders', ['data' => ReportRepository::getOrderReport(),]);
    }
}
