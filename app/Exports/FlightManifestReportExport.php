<?php

namespace App\Exports;

use App\Repository\Reporting\ReportRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FlightManifestReportExport implements FromView
{
    public function view(): View
    {
        return view('partials.reports.tables.flight-manifest', ['data' => ReportRepository::getFlightManifestReport(),]);
    }
}
