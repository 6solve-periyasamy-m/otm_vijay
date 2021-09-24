<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Airline;
use App\Models\Airport;


class AirlinesController extends ApiController
{
    public function getAirlines()
    {
        $airlines = Airline::orderBy('name')->get();

        return response()->json(["success" => true, "data" => $airlines->toArray()]);
    }

    public function getAirports($location_id = null, $region_id = null)
    {
        $airport = new Airport();
        $airport = $airport->select('airports.*')
                    ->join('locations', 'location_id', 'locations.id')
                    ->join('regions', 'locations.region_id', 'regions.id');
        if (isset($location_id)) {
            $airport = $airport->where('location_id', $location_id);
        }
        if (isset($region_id)) {
            $airport = $airport->where('region_id', $region_id);
        }

        $airports = $airport->orderBy('name', 'asc')->get()->toArray();
        $airports = array_combine(array_column($airports, 'id'), $airports);

        return response()->json(["success" => true, "airports" => $airports]);
    }

}
