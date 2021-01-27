<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class FlightInventory extends Model
{
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class);
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
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

        // Because the date and time are in seperate variables, I can't have these Carbon objects be dynamically created by the model
        // the same way as I've done the others. This one will have to stay this way.
        // DEBUG var_dump(Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('d/m/Y H:i'));dd();
        //
        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight->departure_date.' '.$this->flight->departure_time)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->flight->arrival_date.' '.$this->flight->arrival_time)->format('d/m/Y H:i');

        //print '<code>';var_dump($this->arrival_time);dd();

       return "{$this->flight->airline->airline_name}｜Departs from: {$departure_airport->location->location_name} - Arrives at: {$arrival_airport->location->location_name}｜Departs: {$departure_date} - Arrives: {$arrival_date}｜Travel Class: {$this->travelClass->title}";
    }

    public function getFlightDetails()
    {

        return "{$this->flight->airline->airline_name} | Departs from: {$this->getDepartureAirport()->location->location_name} - Arrives at: {$this->getArrivalAirport()->location->location_name} ";

    }

    public $additional_attributes = ['Flight_for_tour'];
}
