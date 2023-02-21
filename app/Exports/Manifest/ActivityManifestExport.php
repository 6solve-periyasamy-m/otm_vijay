<?php

namespace App\Exports\Manifest;

use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActivityManifestExport implements FromView
{
    private HasActivityManifest $manifest;

    public function __construct(HasActivityManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function view(): View
    {
        return view('partials.reports.tables.manifest.activity', ['data' => ActivityManifestRepository::generateReport($this->manifest),]);
    }
}
