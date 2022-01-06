<?php

namespace App\Models;

use App\Repository\FlightComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use StringFormatter;

class FlightInventoryTour extends Model
{
    public $additional_attributes = ['flight_inventory_for_tour','tour_name'];
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'flight_inventory_id', 'tour_component_type', 'flight_type', 'tour_sales_price',];
    protected $cascadeDeletes = ['orders', 'upgrades'];

    public static function getValidationRules()
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'flight_type' => [
                'required',
                Rule::in(['Inbound', 'Outbound']),
            ],
            'tour_sales_price' => 'required|numeric',
            'flight_inventory_id' => 'required|exists:flight_inventories,id'
        ];
    }

    public function flightInventory()
    {
        return $this->belongsTo(FlightInventory::class);
    }

    public function getFlightInventoryForTourAttribute()
    {
        return "{$this->flight_type} {$this->flight->flight_number}";
    }

    public function orders()
    {
        return $this->hasMany(OrderFlight::class, 'flight_inventory_tour_id');
    }

    public function upgrades() {
        return $this->hasMany(FlightInventoryTourUpgrade::class, 'base_id');
    }

    public function parent() {
        return FlightComponentRepository::getParentComponent($this);
    }
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        $inventory = $this->flightInventory;
        $component = $inventory->flight;
        return $component->airline->name . '('. $inventory->flight_number . ')' . $component->departureAirport->name . ' to ' .  $component->arrivalAirport->name .
            ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function getTourNameAttribute()
    {
        return $this->tour->name;
    }

    public function inventory()
    {
        return $this->flightInventory();
    }
}
