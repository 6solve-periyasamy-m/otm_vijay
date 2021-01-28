<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Accommodation extends Model
{

    public function getAccommodationAttribute()
    {
        return "{$this->title} - {$this->region->region_name}";
    }

    public $additional_attributes = ['accommodation'];

    public function orderAccommodation()
    {
        return $this->hasMany(OrdersAccommodation::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

}
