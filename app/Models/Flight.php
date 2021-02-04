<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Flight extends Model
{
    public $additional_attributes = ['flight_details'];

    public function flightInventory()
    {
        return $this->hasMany(FlightInventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id', 'id');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id', 'id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function getFlightDetailsAttribute()
    {
        return "{$this->airline->airline_name} | Departs from: {$this->departureAirport->location->location_name} - Arrives at: {$this->arrivalAirport->location->location_name} ";
    }

}
