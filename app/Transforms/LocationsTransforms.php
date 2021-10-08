<?php

namespace App\Transforms;

use App\Models\Country;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Region;

interface LocationsTransformsInterface {
    public static function getAvailableSelectLocations($filter);
    public static function getAvailableSelectRegions($filter);
    public static function getAvailableSelectCountries($filter);
    public static function getAvailableSelectLocationTypes($filter);
    public static function getSelectedLocation($id);
    public static function getSelectedRegion($id);
    public static function getSelectedCountry($id);
    public static function getSelectedLocationType($id);
}

class LocationsTransforms implements LocationsTransformsInterface
{

    public static function getAvailableSelectLocations($filter)
    {
        $data = [];
        foreach (Location::all() as $location) {
            $subData = [];
            $subData['id'] = $location->id;
            $subData['text'] = $location->name . ' - ' . $location->region->name . ' - ' . $location->region->country->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getAvailableSelectRegions($filter)
    {
        $data = [];
        foreach (Region::all() as $region) {
            $subData = [];
            $subData['id'] = $region->id;
            $subData['text'] = $region->name . ' - ' . $region->country->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getAvailableSelectCountries($filter)
    {
        $data = [];
        foreach (Country::all() as $country) {
            $subData = [];
            $subData['id'] = $country->id;
            $subData['text'] = $country->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getAvailableSelectLocationTypes($filter)
    {
        $data = [];
        foreach (LocationType::all() as $locationType) {
            $subData = [];
            $subData['id'] = $locationType->id;
            $subData['text'] = $locationType->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
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
