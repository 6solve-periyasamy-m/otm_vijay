<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Country;

class CountryApiController extends ApiController
{
    public function getCountries()
    {
        $country = new Country();
        $countries = $country->orderBy('id')->get();

        return response()->json(['success' => true, 'countries' => $countries]);
    }
}
