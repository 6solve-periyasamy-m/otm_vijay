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

    public function getActivityInventoryComponents(Request $request, Tour $tour) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getActivityInventoryDataTable($tour, $dateFrom, $dateTo);
    }

    public function getFlightInventoryComponents(Request $request, Tour $tour) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getFlightInventoryDataTable($tour, $dateFrom, $dateTo);
    }

    public function getTransportInventoryComponents(Request $request, Tour $tour) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getTransportInventoryDataTable($tour, $dateFrom, $dateTo);
    }
}
