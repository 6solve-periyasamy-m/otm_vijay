<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Airport extends Model
{
    public function flightInventory()
    {
        return $this->hasManyThrough(FlightInventory::class, Flight::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public static function getAirportById($airport_id)
    {
        return Airport::where('id', $airport_id)->first();
    }
}
