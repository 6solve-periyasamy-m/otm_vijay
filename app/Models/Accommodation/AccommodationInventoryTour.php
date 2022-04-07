<?php

namespace App\Models\Accommodation;

use App\Models\Group;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Tour;
use App\Repository\AccommodationComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use StringFormatter;

class AccommodationInventoryTour extends Model
{
    use HasFactory, CascadeSoftDeletes, SoftDeletes;

    protected $fillable = ['tour_id', 'accommodation_inventory_id', 'tour_component_type', 'tour_sales_price','is_template'];
    protected $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
    public $additional_attributes = ['tour_name',];

    public static function getValidationRules(): array
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

    public function accommodationInventory(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderAccommodation::class, 'accommodation_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): AccommodationInventoryTour
    {
        return AccommodationComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function addToOrder(Group $group): OrderAccommodation
    {
        return OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
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

}
