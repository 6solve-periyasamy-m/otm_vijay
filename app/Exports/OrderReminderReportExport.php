<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderReminderReportExport implements FromView
{
    public function __construct($max = 7, $min = -1000) {
        $this->max = $max;
        $this->min = $min;
    }

    public function view(): View
    {
        return view('partials.reports.tables.abandoned-bookings', ['data' => ReportRepository::getRemindersReport($this->max, $this->min),]);
    }
}
