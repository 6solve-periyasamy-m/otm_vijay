<?php

namespace App\Transforms;

use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Location\Country;
use App\Models\Location\Currency;
use App\Models\Location\LocationType;

interface LocationsTransformsInterface {
    public static function getAvailableSelectCountries($filter);
    public static function getAvailableSelectLocationTypes($filter);
    public static function getSelectedCountry($id);
    public static function getSelectedLocationType($id);
    public static function getAddresses($filter, $includeCustomer = false);
    public static function getSelectedAddress($id);
    public static function getCurrencies($filter);
    public static function getSelectedCurrency($id);
}

class LocationsTransforms implements LocationsTransformsInterface
{
    public static function getAvailableSelectCountries($filter)
    {
        $data = [];
        foreach (Country::orderBy('name')->get() as $country) {
            $subData = [];
            $subData['id'] = $country->id;
            $subData['text'] = $country?->name . ' - ' . $country->alpha_code;
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

    public static function getSelectedCountry($id) {
        if ($id == 0) return null;
        $country = Country::findOrFail($id);
        $data = [];
        $data['id'] = $country->id;
        $data['text'] = $country?->name . ' - ' . $country->alpha_code;
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
        if ($includeCustomer) {
            $addresses = Address::all();
        } else {
            $addresses = Address::where('address_parent_id', '!=', AddressParent::getParentId('customer'))->get();
        }
        foreach ($addresses as $address) {
            if (!(isset($address->locationType) || $includeCustomer)) continue; // Skip customer addresses unless filtered/included
            $subData = [];
            $subData['id'] = $address->id;
            $subData['text'] = $address->name . ' - ' . (isset($address->locationType) ?  $address->locationType->name : 'Customer Address') . ' - ' . $address->addressParent->name . " - {$address->__toString()}";
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedAddress($id) {
        if ($id == 0) return null;
        $address = Address::findOrFail($id);
        $data = [];
        $data['id'] = $address->id;
        $data['text'] = $address->name . ' - ' . (isset($address->locationType) ?  $address->locationType->name : 'Customer Address') . ' - ' . $address->addressParent->name . " - {$address->__toString()}";
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
