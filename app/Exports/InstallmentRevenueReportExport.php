<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InstallmentRevenueReportExport implements FromView
{
    public function __construct($max = 7, $min = -1000) {
        $this->max = $max;
        $this->min = $min;
    }

    public function view(): View
    {
        return view('partials.reports.tables.installment-revenue', ['data' => ReportRepository::getInstallmentRevenueReport(),]);
    }
}
