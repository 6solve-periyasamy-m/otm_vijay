<?php

namespace App\Http\Controllers;

use App\Repository\Reporting\Manifest\ActivityManifestRepository;

class ManifestController extends Controller
{
    public function viewActivity()
    {
        return ActivityManifestRepository::viewReport(new ActivityManifestRepository(), 'reports.manifest.activity.export');
    }

    public function exportActivity($extension)
    {
        return ActivityManifestRepository::exportReport(new ActivityManifestRepository(), $extension);
    }
}
