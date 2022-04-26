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

    protected $fillable = ['tour_id', 'accommodation_inventory_id', 'tour_component_type', 'tour_sales_price','is_template'];
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

    public function upgrades()
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents()
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent()
    {
        return AccommodationComponentRepository::getParentComponent($this);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString()
    {
        $inventory = $this->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function inventory()
    {
        return $this->accommodationInventory();
    }

    public function getUpgradeKeyMap(int $required = 1): array
    {
        $upgrades = $this->upgrades;
        $included = $this;
        $keys = [];
        if (empty($upgrades->all())) {
            $upgrades = $this->parent()->upgrades;
            $included =  $this->parent();
        }
        if ($included->available_stock > $required-1) {
            $keys[0] = 'Included - ' . StringFormatter::formatCurrency(0);
        }
        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->available_stock <= $required-1) continue;
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

    public function addToOrder(Group $group)
    {
        return OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $this->id,
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
