<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class FlightInventory extends Model
{

    public function tour()
    {
        return $this->belongsToMany(Tour::class);
    }

    public function flight()
    {
        return $this->belongto(Flight::class);
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
    }

    public function getFlightForTourAttribute()
    {
        $flight = Flight::where('id', $this->flight_id)->first();
        $departure_airport = Airport::where('id', $flight->departure_airport_id)->first();
        $departure_location = Location::where('id', $departure_airport->location_id)->first();
        $arrival_airport = Airport::where('id', $flight->arrival_airport_id)->first();
        $arrival_location = Location::where('id', $arrival_airport->location_id)->first();
        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $flight->departure_date.''.$this->departure_time)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $flight->arrival_date.''.$this->arrival_time)->format('d/m/Y H:i');

       return "{$flight->airline->airline_name}｜Departs from: {$departure_location->location_name} - Arrives at: {$arrival_location->location_name}｜Departs: {$departure_date} - Arrives: {$arrival_date}｜Travel Class: {$this->travelClass->title}";
    }
    public $additional_attributes = ['Flight_for_tour'];
}
