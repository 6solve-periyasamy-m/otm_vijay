<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AbandonedBookingsReportExport implements FromView
{
    public function __construct(protected bool $hide = false)
    {

    }

    public function view(): View
    {
        return view('partials.reports.tables.abandoned-bookings', ['data' => ReportRepository::getAbandonedBookingsReport(null, $this->hide),]);
    }
}
