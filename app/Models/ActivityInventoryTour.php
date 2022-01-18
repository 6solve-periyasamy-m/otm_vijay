<?php

namespace App\Models;

use App\Repository\AccommodationComponentRepository;
use App\Repository\ActivityComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use StringFormatter;

class ActivityInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['orders', 'upgrades'];
    protected $fillable = ['tour_id', 'activity_inventory_id', 'tour_component_type', 'tour_sales_price',];
    public $additional_attributes = ['tour_name',];

    public static function getValidationRules()
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'activity_inventory_id' => 'required|exists:activity_inventories,id'
        ];
    }

    public function activityInventory()
    {
        return $this->belongsTo(ActivityInventory::class);
    }

    public function orders()
    {
        return $this->hasMany(OrderActivity::class, 'activity_inventory_tour_id');
    }

    public function upgrades() {
        return $this->hasMany(ActivityInventoryTourUpgrade::class, 'base_id');
    }

    public function parent() {
        return ActivityComponentRepository::getParentComponent($this);
    }

    public function tour() {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        $inventory = $this->activityInventory;
        $component = $inventory->activity;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->starts_at) . ' to ' . StringFormatter::formatDateTime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
    }

    public function getTourNameAttribute()
    {
        return $this->tour->name;
    }

    public function inventory()
    {
        return $this->activityInventory();
    }
}
