<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;


class Flight extends Model
{
    use HasFactory;
    use SoftDeletes;

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

        return "{$this->airline->name} | Departs from: {$this->departureAirport->location->name} - Arrives at: {$this->arrivalAirport->location->name}";
        //return "{$this->airline->name} | Departs {$departs} from: {$this->departureAirport->location->name} - Arrives {$arrives} at: {$this->arrivalAirport->location->name} ";
    }

}
