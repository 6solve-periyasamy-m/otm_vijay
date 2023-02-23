<?php

namespace App\Exports\Manifest;

use App\Repository\Interfaces\Manifest\HasTransportManifest;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransportManifestExport implements FromView
{
    private HasTransportManifest $manifest;

    public function __construct(HasTransportManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function view(): View
    {
        return view('partials.reports.tables.manifest.transport', ['data' => TransportManifestRepository::generateReport($this->manifest),]);
    }
}
