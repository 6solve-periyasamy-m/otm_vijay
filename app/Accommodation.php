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
}
