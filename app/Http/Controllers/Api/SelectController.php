<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccommodationRepository;
use App\Transforms\ActivityTransforms;
use App\Repository\LocationsRepository;
use App\Transforms\FlightTransforms;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function getLocations(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsRepository::getAvailableSelectLocations($filter);
    }

    public function getRegions(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsRepository::getAvailableSelectRegions($filter);
    }

    public function getCountries(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsRepository::getAvailableSelectCountries($filter);
    }

    public function getLocationTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsRepository::getAvailableSelectLocationTypes($filter);
    }

    public function getSelectedLocation($id) {
        return LocationsRepository::getSelectedLocation($id);
    }

    public function getSelectedRegion($id) {
        return LocationsRepository::getSelectedRegion($id);
    }

    public function getSelectedCountry($id) {
        return LocationsRepository::getSelectedCountry($id);
    }

    public function getSelectedLocationType($id) {
        return LocationsRepository::getSelectedLocationType($id);
    }

    public function getRoomTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationRepository::getSelectRoomTypes($filter);
    }

    public function getSelectedRoomType($id) {
        return AccommodationRepository::getSelectedRoomType($id);
    }

    public function getBoardTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationRepository::getSelectBoardTypes($filter);
    }

    public function getSelectedBoardType($id) {
        return AccommodationRepository::getSelectedBoardType($id);
    }

    public function getActivityTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectActivityTypes($filter);
    }

    public function getTicketTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectTicketTypes($filter);
    }

    public function getSelectedActivityType($id) {
        return ActivityTransforms::getSelectedActivityType($id);
    }

    public function getSelectedTicketTypes($id) {
        return ActivityTransforms::getSelectedTicketType($id);
    }

    public function getAirports(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectAirports($filter);
    }

    public function getAirlines(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectAirlines($filter);
    }

    public function getSelectedAirport($id) {
        return FlightTransforms::getSelectedAirport($id);
    }

    public function getSelectedAirline($id) {
        return FlightTransforms::getSelectedAirline($id);
    }
}
