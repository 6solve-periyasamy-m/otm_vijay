<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Location extends Model
{
    use SoftDeletes;

    public static function getLocationById($location_id)
    {
        return Location::where('id', $location_id)->first();
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
