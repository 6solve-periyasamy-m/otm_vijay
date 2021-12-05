<?php

namespace App\Repository;

use App\Models\Address;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;

interface LocationsRepositoryInterface
{
    public static function updateCountry($ccn3, $cca3, $commonName, $dialing_code, $currencies);
}

class LocationsRepository implements LocationsRepositoryInterface
{
    public static function updateCountry($ccn3, $cca3, $commonName, $dialing_code, $currencies)
    {
        $country = Country::where('numeric_code', $ccn3)->first();
        if (!isset($country)) {
            $country = Country::make();
            $country->numeric_code = $ccn3;
        }
        $country->alpha_code = $cca3;
        $country->name = $commonName;
        $country->dialing_code = $dialing_code;
        $country->save();
        foreach ($currencies as $code => $data) {
            self::processCurrencyForCountry($country, $code, $data['name'], $data['symbol'] ?? null);
        }
    }

    private static function processCurrencyForCountry(Country $country, $code, $name, $symbol)
    {
        $currency = Currency::where('code', $code)->first();
        if (isset($currency)) {
            foreach ($currency->countries as $cCountry) { if ($cCountry->id == $country->id) return; }
        } else {
            $currency = Currency::create(['code' => $code, 'name' => $name, 'symbol' => $symbol, ]);
        }
        $currency->countries()->save($country);
    }

    public static function storeAddress($address, $addressParent, $name, $locationType,
                                        $addressLine1, $addressLine2, $addressLine3, $town, $region,
                                        $country, $postcode)
    {
        $data = [
            'name' => $name,
            'address_parent_id' => $addressParent,
            'location_type_id' => $locationType,
            'address_line_1' => $addressLine1,
            'address_line_2' => $addressLine2,
            'address_line_3' => $addressLine3,
            'town' => $town,
            'region'=> $region,
            'country_id' => $country,
            'postcode' => $postcode,
        ];
        if (isset($address)) {
            $address->update($data);
            $address->save();
            return $address;
        } else {
            return Address::create($data);
        }
    }

    public static function storeAddressFromGenericRequest($address, $addressParent, Request $request, $name, $prefix = '')
    {
        return self::storeAddress($address,
            $addressParent,
            $name,
            $request->input($prefix . 'location_type_id'),
            $request->input($prefix . 'address_line_1'),
            $request->input($prefix . 'address_line_2'),
            $request->input($prefix . 'address_line_3'),
            $request->input($prefix . 'town'),
            $request->input($prefix . 'region'),
            $request->input($prefix . 'country_id'),
            $request->input($prefix . 'postcode'),
        );
    }

    public static function cloneAddressToAddress(Address $fromAddress, int $addressParent, Address $toAddress = null) {
        if (isset($toAddress)) {
            $data = $toAddress->toArray();
            unset($data['id']);
            $data['address_parent_id'] = $addressParent;
            $toAddress->update($data);
        } else {
            $toAddress = $fromAddress->replicate();
            $toAddress->address_parent_id = $addressParent;
        }
        $toAddress->save();
        return $toAddress;
    }

    public static function getCurrencyIdByCode(string $code) {
        $currency = Currency::where('code', '=', $code)->first();
        return isset($currency) ? $currency->id : null;
    }
}
