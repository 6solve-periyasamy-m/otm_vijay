<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Flight\Airline;
use App\Models\Flight\Airport;


class AirlinesController extends ApiController
{
    public function getAirlines()
    {
        $airlines = Airline::orderBy('name')->get();

        return response()->json(["success" => true, "data" => $airlines->toArray()]);
    }

    public function getAirports()
    {
        $airport = new Airport();
        $airport = $airport->select('airports.*')
                    ->join('addresses', 'airports.address_id', 'addresses.id');

        $airports = $airport->orderBy('name', 'asc')->get()->toArray();
        $airports = array_combine(array_column($airports, 'id'), $airports);

        return response()->json(["success" => true, "airports" => $airports]);
    }

}
