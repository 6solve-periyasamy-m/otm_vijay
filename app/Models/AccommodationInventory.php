<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Jahondust\ModelLog\Traits\ModelLogging;

class AccommodationInventory extends Model
{
    use HasFactory;

    // use ModelLogging;
    use SoftDeletes, CascadeSoftDeletes;

    public $additional_attributes = ['Accommodation_for_tour'];
    protected $fillable = ['accommodation_id', 'room_type_id', 'board_type_id', 'check_in', 'check_in_time_confirmed', 'check_out', 'check_out_time_confirmed', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'notes', 'currency_id'];
    protected $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public static function getValidationRules()
    {
        return [
            'room_type_id' => 'required|exists:room_types,id',
            'board_type_id' => 'required|exists:board_types,id',
            'check_in' => 'date',
            'check_out' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
        ];
    }

    public static function findByTour($tour_id)
    {
        return AccommodationInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('component_type')->get();
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    // public function OrdersAccommodation()
    // {
    //     return $this->belongsTo(OrdersAccommodation::class);
    // }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'accommodation_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function boardType()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function tourComponents()
    {
        return $this->hasMany(AccommodationInventoryTour::class, 'accommodation_inventory_id');
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, AccommodationInventoryTour::class, 'accommodation_inventory_id', 'id', 'id');
    }

    //TODO: move to Repo

    public function getAccommodationForTourAttribute()
    {
        $check_in = !is_null($this->check_in) ? $this->check_in->format('d/m/Y H:i') : "Unconfirmed";
        $check_out = !is_null($this->check_out) ? $this->check_out->format('d/m/Y H:i') : "Unconfirmed";

        return "{$this->accommodation->name} - {$this->accommodation->region->name}｜Check in: {$check_in} - Check out: {$check_out}｜Room Type: {$this->roomType->name} - Board Type: {$this->boardType->name}";
    }
}

$logFields = ['accommodation_id', 'purchase_price'];
