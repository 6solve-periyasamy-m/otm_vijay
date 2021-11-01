<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Jahondust\ModelLog\Traits\ModelLogging;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationInventory extends Model
{
    use HasFactory;
    // use ModelLogging;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['accommodation_id','room_type_id','board_type_id','check_in','checked_in','check_out','checked_out','fit_selectable','stock','purchase_price','sales_price','notes',];
    protected $cascadeDeletes = ['tourComponents'];
    const RULES = [
        'room_type_id' => 'required|exists:room_types,id',
        'board_type_id' => 'required|exists:board_types,id',
        'check_in' => 'date',
        'check_out' => 'date',
        'stock' => 'required|numeric|integer',
        'purchase_price' => 'required|numeric',
        'sales_price' => 'required|numeric',
        'currency' => 'required|size:3',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
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

    public function tourComponents() {
        return $this->hasMany(AccommodationInventoryTour::class, 'accommodation_inventory_id');
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, AccommodationInventoryTour::class, 'accommodation_inventory_id', 'id', 'id');
    }

    public function getAccommodationForTourAttribute()
    {
        $check_in = !is_null($this->check_in) ? $this->check_in->format('d/m/Y H:i') : "Unconfirmed";
        $check_out = !is_null($this->check_out) ? $this->check_out->format('d/m/Y H:i') : "Unconfirmed";

        return "{$this->accommodation->title} - {$this->accommodation->region->name}｜Check in: {$check_in} - Check out: {$check_out}｜Room Type: {$this->roomType->name} - Board Type: {$this->boardType->board_type_name}";
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
