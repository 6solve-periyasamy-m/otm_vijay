<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;
use Carbon\Carbon;


class AccommodationInventory extends Model
{
    use HasFactory, ModelLogging;
<<<<<<< HEAD
    
    protected $casts = [
        'check_in_date_time' => 'datetime',
        'check_out_date_time' => 'datetime',
    ];
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class);
=======

    public function region()
    {
        return $this->hasOneThrough(Region::class, Accommodation::class, 'id', 'accommodation_id', 'region_id');
    }
    public function OrdersAccommodation()
    {
        return $this->belongsTo(OrdersAccommodation::class);
>>>>>>> add-orders
    }

    public function boardType()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
<<<<<<< HEAD

    public function getAccommodationForTourAttribute()
    {
        $check_in_date_time = $this->check_in_date_time->format('d/m/Y H:i');
        $check_out_date_time = $this->check_out_date_time->format('d/m/Y H:i');

        return "{$this->accommodation->title} - {$this->accommodation->region->region_name}｜Check in: {$check_in_date_time} - Check out: {$check_out_date_time}｜Room Type: {$this->roomType->room_type_name} - Board Type: {$this->boardType->board_type_name}";
    }
    public $additional_attributes = ['Accommodation_for_tour'];
}
$logFields = ['accommodation_id', 'purchase_price'];
=======
}

    $logFields = ['accommodation_id','purchase_price'];
>>>>>>> add-orders
