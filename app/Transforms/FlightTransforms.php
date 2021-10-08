<?php

namespace App\Transforms;

use App\Models\Airline;
use App\Models\Airport;

interface FlightTransformsInterface {
    public static function getSelectAirlines($filter);
    public static function getSelectAirports($filter);
    public static function getSelectedAirline($id);
    public static function getSelectedAirport($id);
}

class FlightTransforms implements FlightTransformsInterface
{

    public static function getSelectAirlines($filter)
    {
        $data = [];
        foreach (Airline::all() as $airline) {
            $subData = [];
            $subData['id'] = $airline->id;
            $subData['text'] = $airline->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectAirports($filter)
    {
        $data = [];
        foreach (Airport::all() as $airport) {
            $subData = [];
            $subData['id'] = $airport->id;
            $subData['text'] = $airport->name . ' - ' . $airport->location->region->country->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedAirline($id)
    {
        if ($id == 0) return null;
        $airline = Airline::findOrFail($id);
        $data = [];
        $data['id'] = $airline->id;
        $data['text'] = $airline->name;
        return $data;
    }

    public static function getSelectedAirport($id)
    {
        if ($id == 0) return null;
        $airport = Airport::findOrFail($id);
        $data = [];
        $data['id'] = $airport->id;
        $data['text'] = $airport->name . ' - ' . $airport->location->region->country->name;
        return $data;
    }
}
