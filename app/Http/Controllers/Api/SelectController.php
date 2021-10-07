<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\AccommodationRepository;
use App\Repository\ActivityRepository;
use App\Repository\LocationsRepository;
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

    public function getActivityTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityRepository::getSelectActivityTypes($filter);
    }

    public function getTicketTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityRepository::getSelectTicketTypes($filter);
    }

    public function getSelectedActivityType($id) {
        return ActivityRepository::getSelectedActivityType($id);
    }

    public function getSelectedTicketTypes($id) {
        return ActivityRepository::getSelectedTicketType($id);
    }
}
