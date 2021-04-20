<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Jahondust\ModelLog\Traits\ModelLogging;


class Accommodation extends Model
{
    //use ModelLogging;
    use HasFactory;

    public function orderAccommodation()
    {
        return $this->belongsTo(OrdersAccommodation::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function getInventoryRelationAttribute()
    {
        return "{$this->title} | {$this->region->region_name}";
    }

    public $additional_attributes = ['inventory_relation'];
}
