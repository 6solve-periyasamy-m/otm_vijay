<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccommodationRepository;
use App\Repository\LocationsRepository;
use App\Transforms\TransportTransforms;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function getLocations() {
        return LocationsRepository::getAvailableSelectLocations();
    }

    public function getRegions() {
        return LocationsRepository::getAvailableSelectRegions();
    }

    public function getCountries() {
        return LocationsRepository::getAvailableSelectCountries();
    }

    public function getLocationTypes() {
        return LocationsRepository::getAvailableSelectLocationTypes();
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

    public function getRoomTypes() {
        return AccommodationRepository::getSelectRoomTypes();
    }

    public function getSelectedRoomType($id) {
        return AccommodationRepository::getSelectedRoomType($id);
    }

    public function getBoardTypes() {
        return AccommodationRepository::getSelectBoardTypes();
    }

    public function getSelectedBoardType($id) {
        return AccommodationRepository::getSelectedBoardType($id);
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
}
