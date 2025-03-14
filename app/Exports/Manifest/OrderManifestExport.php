<?php

namespace App\Exports\Manifest;

use App\Repository\Interfaces\Manifest\HasOrderManifest;
use App\Repository\Reporting\Manifest\OrderManifestRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderManifestExport implements FromView
{
    private HasOrderManifest $manifest;

    public function __construct(HasOrderManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function view(): View
    {
        return view('partials.reports.tables.manifest.order', ['data' => (new OrderManifestRepository($this->manifest))->getRows(),]);
    }
}
