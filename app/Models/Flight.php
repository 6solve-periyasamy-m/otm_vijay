<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Flight extends Model
{
<<<<<<< HEAD
    
=======

>>>>>>> add-orders
    public function flightInventory()
    {
        return $this->hasMany(FlightInventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function airport()
    {
<<<<<<< HEAD
        return $this->belongsTo(Airport::class);
=======
        return $this->belongsTo(Airport::class, 'id', 'departure_airport_id');
>>>>>>> add-orders
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

}
