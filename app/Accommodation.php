<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Accommodation extends Model
{
    public function orderAccommodation()
    {
        return $this->belongsTo(OrdersAccommodation::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

}
