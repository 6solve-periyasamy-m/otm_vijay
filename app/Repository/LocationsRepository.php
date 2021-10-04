<?php

namespace App\Repository;

use App\Models\Country;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Region;

interface LocationsRepositoryInterface {
    public static function getAvailableSelectLocations();
    public static function getAvailableSelectRegions();
    public static function getAvailableSelectCountries();
    public static function getAvailableSelectLocationTypes();
    public static function getSelectedLocation($id);
    public static function getSelectedRegion($id);
    public static function getSelectedCountry($id);
    public static function getSelectedLocationType($id);
}

class LocationsRepository implements LocationsRepositoryInterface
{

    public static function getAvailableSelectLocations()
    {
        $data = [];
        foreach (Location::all() as $location) {
            $lData = [];
            $lData['id'] = $location->id;
            $lData['text'] = $location->name . ' - ' . $location->region->name . ' - ' . $location->region->country->name;
            $data['results'][] = $lData;
        }
        return $data;
    }

    public static function getAvailableSelectRegions()
    {
        $data = [];
        foreach (Region::all() as $region) {
            $rData = [];
            $rData['id'] = $region->id;
            $rData['text'] = $region->name . ' - ' . $region->country->name;
            $data['results'][] = $rData;
        }
        return $data;
    }

    public static function getAvailableSelectCountries()
    {
        $data = [];
        foreach (Country::all() as $country) {
            $cData = [];
            $cData['id'] = $country->id;
            $cData['text'] = $country->name;
            $data['results'][] = $cData;
        }
        return $data;
    }

    public static function getAvailableSelectLocationTypes()
    {
        $data = [];
        foreach (LocationType::all() as $locationType) {
            $ltData = [];
            $ltData['id'] = $locationType->id;
            $ltData['text'] = $locationType->name;
            $data['results'][] = $ltData;
        }
        return $data;
    }

    public static function getSelectedLocation($id) {
        if ($id == 0) return null;
        $location = Location::findOrFail($id);
        $data = [];
        $data['id'] = $location->id;
        $data['text'] = $location->name . ' - ' . $location->region->name . ' - ' . $location->region->country->name;
        return $data;
    }

    public static function getSelectedRegion($id) {
        if ($id == 0) return null;
        $region = Region::findOrFail($id);
        $data = [];
        $data['id'] = $region->id;
        $data['text'] = $region->name . ' - ' . $region->country->name;
        return $data;
    }

    public static function getSelectedCountry($id) {
        if ($id == 0) return null;
        $country = Country::findOrFail($id);
        $data = [];
        $data['id'] = $country->id;
        $data['text'] = $country->name;
        return $data;
    }

    public static function getSelectedLocationType($id) {
        if ($id == 0) return null;
        $locationType = LocationType::findOrFail($id);
        $data = [];
        $data['id'] = $locationType->id;
        $data['text'] = $locationType->name;
        return $data;
    }
}
