<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tour extends Model
{
    public function flightinventory()
    {
        return $this->belongsToMany(FlightInventory::class);
    }

    public function accommodationinventory()
    {
        return $this->belongsToMany(AccommodationInventory::class);
    }

    public function transportInventory()
    {
        return $this->belongsToMany(TransportInventory::class);
    }
}
