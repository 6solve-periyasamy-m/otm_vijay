<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transforms\TourTransforms;
use Illuminate\Http\Request;


class DataTablesController extends Controller
{
    public function getAccommodationInventoryComponents(Request $request) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getAccommodationInventoryDataTable($dateFrom, $dateTo);
    }

    public function getActivityInventoryComponents(Request $request) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getActivityInventoryDataTable($dateFrom, $dateTo);
    }

    public function getFlightInventoryComponents(Request $request) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getFlightInventoryDataTable($dateFrom, $dateTo);
    }

    public function getTransportInventoryComponents(Request $request) {
        $dateFrom = $request->has('dateFrom') ? $request->input('dateFrom') : "";
        $dateTo = $request->has('dateTo') ? $request->input('dateTo') : "";
        return TourTransforms::getTransportInventoryDataTable($dateFrom, $dateTo);
    }
}
