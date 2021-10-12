<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transforms\ActivityTransforms;
use App\Transforms\TourTransforms;
use App\Transforms\TransportTransforms;
use App\Transforms\FlightTransforms;
use Illuminate\Http\Request;
use App\Transforms\AccommodationTransforms;
use App\Transforms\LocationsTransforms;

class SelectController extends Controller
{
    public function getLocations(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectLocations($filter);
    }

    public function getRegions(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectRegions($filter);
    }

    public function getCountries(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectCountries($filter);
    }

    public function getLocationTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectLocationTypes($filter);
    }

    public function getSelectedLocation($id) {
        return LocationsTransforms::getSelectedLocation($id);
    }

    public function getSelectedRegion($id) {
        return LocationsTransforms::getSelectedRegion($id);
    }

    public function getSelectedCountry($id) {
        return LocationsTransforms::getSelectedCountry($id);
    }

    public function getSelectedLocationType($id) {
        return LocationsTransforms::getSelectedLocationType($id);
    }

    public function getRoomTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectRoomTypes($filter);
    }

    public function getSelectedRoomType($id) {
        return AccommodationTransforms::getSelectedRoomType($id);
    }

    public function getBoardTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectBoardTypes($filter);
    }

    public function getSelectedBoardType($id) {
        return AccommodationTransforms::getSelectedBoardType($id);
    }

    public function getTransportTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectTransportTypes($filter);
    }

    public function getOperators(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectOperators($filter);
    }

    public function getTravelClasses(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectTravelClasses($filter);
    }

    public function getSelectedTransportType($id) {
        return TransportTransforms::getSelectedTransportType($id);
    }

    public function getSelectedOperator($id) {
        return TransportTransforms::getSelectedOperator($id);
    }

    public function getSelectedTravelClass($id) {
        return TransportTransforms::getSelectedTravelClass($id);
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

    public function getEvents(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectEvents($filter);
    }

    public function getTours(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectTours($filter);
    }

    public function getSelectedEvent($id) {
        return TourTransforms::getSelectedEvent($id);
    }

    public function getSelectedTour($id) {
        return TourTransforms::getSelectedTour($id);
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
