<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jahondust\ModelLog\Traits\ModelLogging;
use Carbon\Carbon;

class AccommodationInventory extends Model
{
    use HasFactory, ModelLogging;

    protected $casts = [
        'check_in_date_time' => 'datetime',
        'check_out_date_time' => 'datetime',
    ];
    public $additional_attributes = ['Accommodation_for_tour'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'accommodation_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function region()
    {
        return $this->hasOneThrough(Region::class, Accommodation::class, 'id', 'accommodation_id', 'region_id');
    }

    // public function OrdersAccommodation()
    // {
    //     return $this->belongsTo(OrdersAccommodation::class);
    // }

    public function boardType()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // public function component_type()
    // {
    //     return $this->hasOneThrough(TourComponentType::class, AccommodationInventoryTour::class, 'accommodation_inventory_id', 'id', 'id');
    // }

    public function getAccommodationForTourAttribute()
    {
        $check_in_date_time = $this->check_in_date_time->format('d/m/Y H:i');
        $check_out_date_time = $this->check_out_date_time->format('d/m/Y H:i');

        return "{$this->accommodation->title} - {$this->accommodation->region->region_name}｜Check in: {$check_in_date_time} - Check out: {$check_out_date_time}｜Room Type: {$this->roomType->room_type_name} - Board Type: {$this->boardType->board_type_name}";
    }

    //TODO: move to Repo
    public static function findByTour($tour_id)
    {
        return AccommodationInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('component_type')->get();
    }
}

$logFields = ['accommodation_id','purchase_price'];
