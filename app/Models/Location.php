<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Location extends Model
{

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

}
