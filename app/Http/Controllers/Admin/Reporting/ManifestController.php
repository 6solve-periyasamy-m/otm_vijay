<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Http\Controllers\Controller;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Reporting\Manifest\MerchandiseManifestRepository;
use App\Repository\Reporting\Manifest\TransportManifestRepository;

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

    public function viewTransport()
    {
        return TransportManifestRepository::viewReport(new TransportManifestRepository(), 'reports.manifest.transport.export');
    }

    public function exportTransport($extension)
    {
        return TransportManifestRepository::exportReport(new TransportManifestRepository(), $extension);
    }

    public function viewMerchandise()
    {
        return MerchandiseManifestRepository::viewReport(new MerchandiseManifestRepository(), 'reports.manifest.transport.export');
    }

    public function exportMerchandise($extension)
    {
        return MerchandiseManifestRepository::exportReport(new MerchandiseManifestRepository(), $extension);
    }
}
