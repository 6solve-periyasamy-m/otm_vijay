<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccommodationRepository;
use App\Repository\LocationsRepository;
use App\Repository\TransportRepository;
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
        $filter = $request->input('filter') != null ? $request->input('filter') : "";
        return TransportRepository::getSelectTransportTypes($filter);
    }

    public function getOperators(Request $request) {
        $filter = $request->input('filter') != null ? $request->input('filter') : "";
        return TransportRepository::getSelectOperators($filter);
    }

    public function getTravelClasses(Request $request) {
        $filter = $request->input('filter') != null ? $request->input('filter') : "";
        return TransportRepository::getSelectTravelClasses($filter);
    }

    public function getSelectedTransportType($id) {
        return TransportRepository::getSelectedTransportType($id);
    }

    public function getSelectedOperator($id) {
        return TransportRepository::getSelectedOperator($id);
    }

    public function getSelectedTravelClass($id) {
        return TransportRepository::getSelectedTravelClass($id);
    }
}
