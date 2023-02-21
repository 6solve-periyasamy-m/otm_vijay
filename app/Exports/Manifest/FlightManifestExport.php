<?php

namespace App\Exports\Manifest;

use App\Repository\Interfaces\Manifest\HasFlightManifest;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FlightManifestExport implements FromView
{
    private HasFlightManifest $manifest;

    public function __construct(HasFlightManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function view(): View
    {
        return view('partials.reports.tables.manifest.flight', ['data' => FlightManifestRepository::generateReport($this->manifest),]);
    }
}
