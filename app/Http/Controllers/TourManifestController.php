<?php

namespace App\Http\Controllers;

use App\Models\Tour\Tour;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\FlightManifestRepository;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
use App\Repository\Reporting\Manifest\TransportManifestRepository;
use Illuminate\Http\Request;

class TourManifestController extends Controller
{
    public function rooming(Request $request, Tour $tour)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::viewReport($tour->repository, 'tours.rooming.export', $notes, ['tour' => $tour,]);
    }

    public function exportRooming(Request $request, Tour $tour, string $extension)
    {
        $notes = !$request->has('notes') || $request->notes == true;
        return RoomingReportRepository::exportReport($tour->repository, $extension, $notes);
    }

    public function activity(Tour $tour)
    {
        return ActivityManifestRepository::viewReport($tour->repository, 'tours.manifest.activity.export', ['tour' => $tour,]);
    }

    public function exportActivity(Tour $tour, string $extension = 'xlsx')
    {
        return ActivityManifestRepository::exportReport($tour->repository, $extension);
    }

    public function flight(Tour $tour)
    {
        return FlightManifestRepository::viewReport($tour->repository, 'tours.manifest.flight.export', ['tour' => $tour,]);
    }

    public function exportFlight(Tour $tour, string $extension = 'xlsx')
    {
        return FlightManifestRepository::exportReport($tour->repository, $extension);
    }

    public function transport(Tour $tour)
    {
        return TransportManifestRepository::viewReport($tour->repository, 'tours.manifest.transport.export', ['tour' => $tour,]);
    }

    public function exportTransport(Tour $tour, string $extension = 'xlsx')
    {
        return TransportManifestRepository::exportReport($tour->repository, $extension);
    }
}
