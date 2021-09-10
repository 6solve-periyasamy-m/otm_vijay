<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Jahondust\ModelLog\Traits\ModelLogging;


class Accommodation extends Model
{
    //use ModelLogging;
    use HasFactory;
    use SoftDeletes;

    public function orderAccommodation()
    {
        return $this->belongsTo(OrdersAccommodation::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function board_type()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function getInventoryRelationAttribute()
    {
        return "{$this->title} | {$this->region->region_name}";
    }

    public function inventory() {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public $additional_attributes = ['inventory_relation'];
}
