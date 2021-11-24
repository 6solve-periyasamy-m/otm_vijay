<?php

namespace App\Transforms;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\FlightInventory;

interface FlightTransformsInterface {
    public static function getSelectAirlines($filter);
    public static function getSelectAirports($filter);
    public static function getSelectedAirline($id);
    public static function getSelectedAirport($id);
    public static function getSelectInventory($filter);
    public static function getSelectedInventory($filter);
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
            $subData['text'] = $airport->name . ' - ' . $airport->address->name;
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
        $data['text'] = $airport->name . ' - ' . $airport->address->name;
        return $data;
    }

    public static function getSelectInventory($filter)
    {
        $data = [];
        foreach (FlightInventory::all() as $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->flight_number . ' - ' . $inventory->flight->departureAirport->name . ' to ' . $inventory->flight->arrivalAirport->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventory($id)
    {
        if ($id == 0) return null;
        $inventory = FlightInventory::findOrFail($id);
        $data = [];
        $data['id'] = $inventory->id;
        $data['text'] = $inventory->flight_number . ' - ' . $inventory->flight->departureAirport->name . ' to ' . $inventory->flight->arrivalAirport->name;
        return $data;
    }
}
