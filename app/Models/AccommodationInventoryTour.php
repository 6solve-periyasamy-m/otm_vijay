<?php

namespace App\Models;

use App\Repository\AccommodationComponentRepository;
use StringFormatter;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class AccommodationInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'accommodation_inventory_id', 'tour_component_type', 'tour_sales_price',];
    protected $cascadeDeletes = ['orders', 'upgrades'];
    public $additional_attributes = ['tour_name',];

    public static function getValidationRules()
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'accommodation_inventory_id' => 'required|exists:accommodation_inventories,id'
        ];
    }

    public function accommodationInventory()
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderAccommodation::class, 'accommodation_inventory_tour_id');
    }

    public function upgrades() {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'base_id');
    }

    public function parent() {
        return AccommodationComponentRepository::getParentComponent($this);
    }

    public function tour() {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        $inventory = $this->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function getTourNameAttribute()
    {
        return $this->tour->name;
    }

    public function inventory()
    {
        return $this->accommodationInventory();
    }
}
