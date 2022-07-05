<?php

namespace Tests\Traits;

use App\Models\Flight\Airline;
use App\Models\Flight\Airport;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use App\Models\TravelClass;

trait TestsFlight
{
    public function generateFlight(Airport $from = null, Airport $to = null, Airline $airline = null): Flight
    {
        if (!isset($from)) $from = $this->generateAirport();
        if (!isset($to)) $to = $this->generateAirport();
        if (!isset($airline)) $airline = $this->generateAirline();
        $flight = Flight::factory()->makeOne();
        $flight->departure_airport_id = $from->id;
        $flight->arrival_airport_id = $to->id;
        $flight->airline_id = $airline->id;
        $flight->save();
        return $flight;
    }

    public function generateFlightInventory(Flight $flight = null, TravelClass $class = null, array $attributes = []): FlightInventory
    {
        if (!isset($flight)) $flight = $this->generateFlight();
        if (!isset($class)) $class = TravelClass::factory()->create();
        $inventory = FlightInventory::factory()->makeOne($attributes);
        $inventory->travel_class_id = $class->id;
        $flight->flightInventory()->save($inventory);
        return $inventory;
    }

    public function generateAirport(): Airport
    {
        return Airport::factory()->create();
    }

    public function generateAirline(): Airline
    {
        return Airline::factory()->create();
    }
}
