<?php

namespace App\Repository;

use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Location\Currency;
use Illuminate\Http\Request;

class LocationsRepository
{
    public static function updateCountry($ccn3, $cca3, $commonName, $dialing_code, $currencies): void
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

    private static function processCurrencyForCountry(Country $country, $code, $name, $symbol): void
    {
        $currency = Currency::where('code', $code)->first();
        if (isset($currency)) {
            foreach ($currency->countries as $cCountry) { if ($cCountry->id == $country->id) return; }
        } else {
            $currency = Currency::create(['code' => $code, 'name' => $name, 'symbol' => $symbol, ]);
        }
        $currency->countries()->save($country);
    }

    public static function getCurrencyIdByCode(string $code) {
        $currency = Currency::where('code', '=', $code)->first();
        return isset($currency) ? $currency->id : null;
    }
}
