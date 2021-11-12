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

    public static function storeAddress($address, $name, $location_type_id,
                                        $address_line_1, $address_line_2, $address_line_3, $town, $region,
                                        $country_id, $postcode)
    {
        $data = [
            'name' => $name,
            'location_type_id' => $location_type_id,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'address_line_3' => $address_line_3,
            'town' => $town,
            'region'=> $region,
            'country_id' => $country_id,
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

    public static function storeAddressFromGenericRequest($address, Request $request, $name, $prefix)
    {
        return self::storeAddress($address,
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

    public static function cloneAddressToAddress(Address $fromAddress, Address $toAddress = null) {
        if (isset($toAddress)) {
            $data = $toAddress->toArray();
            unset($data['id']);
            $toAddress->update($data);
        } else {
            $toAddress = $fromAddress->replicate();
        }
        $toAddress->save();
        return $toAddress;
    }
}
