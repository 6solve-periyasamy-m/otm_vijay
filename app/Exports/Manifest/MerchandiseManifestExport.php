<?php

namespace App\Exports\Manifest;

use App\Repository\Interfaces\Manifest\HasMerchandiseManifest;
use App\Repository\Reporting\Manifest\MerchandiseManifestRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MerchandiseManifestExport implements FromView
{
    private HasMerchandiseManifest $manifest;

    public function __construct(HasMerchandiseManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function view(): View
    {
        return view('partials.reports.tables.manifest.merchandise', ['data' => MerchandiseManifestRepository::generateReport($this->manifest),]);
    }
}
