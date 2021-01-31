<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlightInventory extends Model
{
    public $additional_attributes = ['Flight_for_tour'];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, FlightInventoryTour::class, 'flight_inventory_id', 'id', 'id');
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'flight_inventory_tour')->withPivot('sales_price');
    }

    public function departureAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public static function findByTour($tour_id)
    {
        return FlightInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function getDepartureAirport()
    {
        return Airport::getAirportById($this->flight->departure_airport_id);
    }

    public function getArrivalAirport()
    {
        return Airport::getAirportById($this->flight->arrival_airport_id);
    }

    public function getFlightForTourAttribute()
    {
        $departure_airport = Airport::getAirportById($this->flight->departure_airport_id);
        $arrival_airport = Airport::getAirportById($this->flight->arrival_airport_id);

        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight->departure_date.' '.$this->flight->departure_time)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight->arrival_date.' '.$this->flight->arrival_time)->format('d/m/Y H:i');

        return "{$this->flight->airline->airline_name}｜Departs from: {$departure_airport->location->location_name} - Arrives at: {$arrival_airport->location->location_name}｜Departs: {$departure_date} - Arrives: {$arrival_date}｜Travel Class: {$this->travelClass->title}";
    }

    public function getFlightDetails()
    {
        return "{$this->flight->airline->airline_name} | Departs from: {$this->getDepartureAirport()->location->location_name} - Arrives at: {$this->getArrivalAirport()->location->location_name} ";
    }
}
