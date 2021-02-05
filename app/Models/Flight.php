<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


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
        $departs = Carbon::parse($this->departure_date)->format('d/m/Y');
        $arrives = Carbon::parse($this->arrival_date)->format('d/m/Y');

        return "{$this->airline->airline_name} | Departs {$departs} from: {$this->departureAirport->location->location_name} - Arrives {$arrives} at: {$this->arrivalAirport->location->location_name} ";
    }

}
