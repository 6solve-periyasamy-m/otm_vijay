<?php

namespace App\Exports;

use App\Models\System\Report;
use App\Repository\BespokeReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BespokeReportExport implements FromView
{
    private $report;

    public function __construct(Report $report) {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('partials.reports.bespoke.output', BespokeReportRepository::showReport($this->report, false));
    }
}
