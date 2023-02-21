<?php

namespace App\Http\Controllers;

use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;

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

    public function viewFlight()
    {
        return FlightManifestRepository::viewReport(new FlightManifestRepository(), 'reports.manifest.flight.export');
    }

    public function exportFlight($extension)
    {
        return FlightManifestRepository::exportReport(new FlightManifestRepository(), $extension);
    }
}
