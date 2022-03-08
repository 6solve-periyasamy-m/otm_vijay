<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Country;

class CountryApiController extends ApiController
{
    public function getCountries()
    {
        $country = new Country();
        $countries = $country->orderBy('name', 'ASC')->get();

        return response()->json(['success' => true, 'countries' => $countries]);
    }
}
