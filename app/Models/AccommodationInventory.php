<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;


class AccommodationInventory extends Model
{
    use HasFactory, ModelLogging;

    public function region()
    {
        return $this->hasOneThrough(Region::class, Accommodation::class, 'id', 'accommodation_id', 'region_id');
    }
    public function OrdersAccommodation()
    {
        return $this->belongsTo(OrdersAccommodation::class);
    }

    public function boardType()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}

    $logFields = ['accommodation_id','purchase_price'];
