<?php

namespace App\Transforms;

use App\Models\Address;
use App\Models\Country;
use App\Models\Currency;
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
    public static function getAddresses($filter, $includeCustomer = false);
    public static function getSelectedAddress($id);
    public static function getCurrencies($filter);
    public static function getSelectedCurrency($id);
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
            $subData['text'] = $country->name . ' - ' . $country->alpha_code;
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
        $data['text'] = $country->name . ' - ' . $country->alpha_code;
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

    public static function getAddresses($filter, $includeCustomer = false) {
        $data = [];
        foreach (Address::all() as $address) {
            if (!(isset($address->locationType) || $includeCustomer)) continue; // Skip customer addresses that have no location type set
            $subData = [];
            $subData['id'] = $address->id;
            $subData['text'] = $address->name . ' - ' . (isset($address->locationType) ?  $address->locationType->name : 'Customer Address') . ' - ' . $address->addressParent->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedAddress($id) {
        if ($id == 0) return null;
        $address = Address::findOrFail($id);
        $data = [];
        $data['id'] = $address->id;
        $data['text'] = $address->name . ' - ' . (isset($address->locationType) ?  $address->locationType->name : 'Customer Address') . ' - ' . $address->addressParent->name;
        return $data;
    }

    public static function getCurrencies($filter) {
        $data = [];
        foreach (Currency::all() as $currency) {
            $subData = [];
            $subData['id'] = $currency->id;
            $subData['text'] = $currency->name . ' - ' . $currency->code;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedCurrency($id) {
        if ($id == 0) return null;
        $currency = Currency::findOrFail($id);
        $data = [];
        $data['id'] = $currency->id;
        $data['text'] = $currency->name . ' - ' . $currency->code;
        return $data;
    }
}
