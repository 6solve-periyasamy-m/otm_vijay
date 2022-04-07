<?php

namespace App\Models;

use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Repository\TransportComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use StringFormatter;

class TransportInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'transport_inventory_id', 'tour_sales_price', 'tour_component_type'];
    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];

    public static function getValidationRules(): array
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

    public function transportInventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderTransport::class, 'transport_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): TransportInventoryTour
    {
        return TransportComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
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

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
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

    public function addToOrder(OrderCustomer $orderCustomer): OrderTransport
    {
        return OrderTransport::create([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }
}
