<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Location extends Model
{

<<<<<<< HEAD
    public static function getLocationById($location_id)
    {
        return Location::where('id', $location_id)->first();
=======
    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
>>>>>>> add-orders
    }

}
