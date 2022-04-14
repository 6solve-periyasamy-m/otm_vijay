<?php

namespace App\Transforms;

use App\Models\Flight\Airline;
use App\Models\Flight\Airport;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Repository\TourRepository;
use StringFormatter;

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
            $subData['text'] = $inventory->flight_number . ' - ' . $inventory->travelClass->name . ' - ' . $inventory->flight->departureAirport->name . ' to ' . $inventory->flight->arrivalAirport->name;
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
        $data['text'] = $inventory->flight_number . ' - ' . $inventory->travelClass->name . ' - ' . $inventory->flight->departureAirport->name . ' to ' . $inventory->flight->arrivalAirport->name;
        return $data;
    }

    public static function getSelectInventoryForFlight(FlightInventoryTour $tourInventory, $filter) {
        $available = TourRepository::getAvailableFlightForUpgrades($tourInventory);
        $data = [];
        foreach ($available as $id => $inventory) {
            $subData = [];
            $subData['id'] = $inventory->id;
            $subData['text'] = $inventory->flight_number . ' - ' . $inventory->travelClass->name . ' - ' . $inventory->departs_at . ' to ' . $inventory->arrives_at;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedInventoryForFlight($id) {
        if ($id == 0) return null;
        $inventory = FlightInventory::findOrFail($id);
        $subData = [];
        $subData['id'] = $inventory->id;
        $subData['text'] = $inventory->flight_number . ' - ' . $inventory->travelClass->name . ' - ' . $inventory->departs_at . ' to ' . $inventory->arrives_at;
        return $subData;
    }

    public static function getAvailableAddons(OrderCustomer $orderCustomer, string $filter)
    {
        $tour = $orderCustomer->order->tour;
        $data = [];
        $owned = [];
        foreach ($orderCustomer->orderFlights as $orderComponent) $owned[] = $orderComponent->tourComponent->id;
        foreach ($tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type  !== "Upgrade") {
                if (in_array($inventoryTour->id, $owned)) continue;
                $subData = [];
                $subData['id'] = $inventoryTour->id;
                $subData['text'] = $inventoryTour . " ({$inventoryTour->tour_component_type})"
                    . ' - ' .
                    ($inventoryTour->tour_component_type === 'Included' ? 'Included with Basic Package' :
                        StringFormatter::formatCurrency($inventoryTour->tour_sales_price));
                if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
            }
        }
        return $data;
    }
}
