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

    protected $fillable = ['tour_id', 'transport_inventory_id', 'tour_sales_price', 'tour_component_type'];
    protected $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
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

    // Only used for Cascading Soft Deletes
    public function upgradeParents()
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'upgrade_id');
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

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function inventory()
    {
        return $this->transportInventory();
    }

    public function getUpgradeKeyMap(): array
    {
        $upgrades = $this->upgrades;
        $keys = [];
        if (empty($upgrades->all())) $upgrades = $this->parent()->upgrades;
        $keys[0] = 'Included - ' . StringFormatter::formatCurrency(0);
        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->available_stock <= 0) continue;
            $keys[$upgrade->id] = $upgrade->description . ' - ' . StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price);
        }
        return $keys;
    }

    public function getCustomerUpgradeKeyMap(): array
    {
        $upgrades = $this->upgrades;
        $keys = [];

        if (empty($upgrades->all())) {
            $upgrades = $this->parent()->upgrades;
        }

        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->id == $this->id) continue;
            if ($upgrade->upgrade->available_stock <= 0) continue;
            if ($this->tour_component_type == 'Included' || $upgrade->upgrade->tour_sales_price >= $this->tour_sales_price) {
                $keys[$upgrade->id] = $upgrade->description . ' - ' . StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price);
            }
        }
        return $keys;
    }

    public function addToOrder(OrderCustomer $orderCustomer)
    {
        return OrderTransport::create([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }

    public function getAvailableStockAttribute()
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function getUsedTourStockAttribute(): int
    {
        $used = 0;
        foreach ($this->orders as $orderComponent) {
            if (!$orderComponent->isCancelled()) $used++;
        }
        return $used;
    }
}
