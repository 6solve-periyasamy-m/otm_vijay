<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BookingOrdersReportExport implements FromView
{
    public function __construct(protected bool $hide = false)
    {

    }

    public function view(): View
    {
        return view('partials.reports.tables.booking-orders', ['data' => ReportRepository::getOnlineOrderReport(),]);
    }
}
