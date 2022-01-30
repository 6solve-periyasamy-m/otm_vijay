<?php

namespace App\Models;

use App\Repository\TransportComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use StringFormatter;

class TransportInventoryTour extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'transport_inventory_id', 'tour_sales_price'];
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
            'transport_inventory_id' => 'required|exists:transport_inventories,id'
        ];
    }

    public function transportInventory()
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderTransport::class, 'transport_inventory_tour_id');
    }

    public function upgrades() {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'base_id');
    }

    public function parent() {
        return TransportComponentRepository::getParentComponent($this);
    }

    public function tour() {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        $inventory = $this->transportInventory;
        $component = $inventory->transport;
        return $component->name . ' (' . $component->departureAddress->name . ' to ' .  $component->arrivalAddress->name . ')'.
            ' (' . $component->transportType->name . ') ' .
            ' (' . StringFormatter::formatDateTime($inventory->departs_at) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function getTourNameAttribute()
    {
        return $this->tour->name;
    }

    public function inventory()
    {
        return $this->transportInventory();
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderTransport
    {
        return OrderTransport::create([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price
        ]);
    }
}
