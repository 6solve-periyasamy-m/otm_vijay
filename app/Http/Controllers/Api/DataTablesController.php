<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Transforms\TourTransforms;
use Illuminate\Http\Request;


class DataTablesController extends Controller
{
    public function getAccommodationInventoryComponents(Request $request, Tour $tour) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getAccommodationInventoryDataTable($tour, $dateFrom, $dateTo);
    }

    public function getActivityInventoryComponents(Request $request) {
        $tourId = $request->has('tourId') ? $request->input('tourId') : null;
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getActivityInventoryDataTable($tourId, $dateFrom, $dateTo);
    }

    public function getFlightInventoryComponents(Request $request) {
        $tourId = $request->has('tourId') ? $request->input('tourId') : null;
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getFlightInventoryDataTable($tourId, $dateFrom, $dateTo);
    }

    public function getTransportInventoryComponents(Request $request) {
        $tourId = $request->has('tourId') ? $request->input('tourId') : null;
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getTransportInventoryDataTable($tourId, $dateFrom, $dateTo);
    }
}
