<?php

namespace App\Http\Controllers;

use App\Models\Tour\Tour;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Reporting\Manifest\RoomingReportRepository;
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
        return ActivityManifestRepository::viewReport($tour->repository, 'tours.manifest.export', ['tour' => $tour,]);
    }

    public function exportActivity(Tour $tour, string $extension = 'xlsx')
    {
        return ActivityManifestRepository::exportReport($tour->repository, $extension);
    }
}
