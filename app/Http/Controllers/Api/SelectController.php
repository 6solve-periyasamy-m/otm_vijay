<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transforms\AccommodationTransforms;
use App\Transforms\LocationsTransforms;

class SelectController extends Controller
{
    public function getLocations() {
        return LocationsTransforms::getAvailableSelectLocations();
    }

    public function getRegions() {
        return LocationsTransforms::getAvailableSelectRegions();
    }

    public function getCountries() {
        return LocationsTransforms::getAvailableSelectCountries();
    }

    public function getLocationTypes() {
        return LocationsTransforms::getAvailableSelectLocationTypes();
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

    public function getRoomTypes() {
        return AccommodationTransforms::getSelectRoomTypes();
    }

    public function getSelectedRoomType($id) {
        return AccommodationTransforms::getSelectedRoomType($id);
    }

    public function getBoardTypes() {
        return AccommodationTransforms::getSelectBoardTypes();
    }

    public function getSelectedBoardType($id) {
        return AccommodationTransforms::getSelectedBoardType($id);
    }
}
