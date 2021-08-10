<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Airport extends Model
{
    use SoftDeletes;

    public function flightInventory()
    {
        return $this->hasManyThrough(FlightInventory::class, Flight::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // public static function getAirportById($airport_id)
    // {
    //     return Airport::where('id', $airport_id)->first();
    // }

    public function flight()
    {
        return $this->hasMany(Flight::class);
    }
}
