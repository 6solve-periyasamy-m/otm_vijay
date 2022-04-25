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

    protected $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
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

    // Only used for Cascading Soft Deletes
    public function upgradeParents()
    {
        return $this->hasMany(ActivityInventoryTourUpgrade::class, 'upgrade_id');
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

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function inventory()
    {
        return $this->activityInventory();
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

    public function getBookingUpgradeKeyMap(int $required = 1): array
    {
        $upgrades = $this->upgrades;
        $included = $this;
        $keys = [];
        if (empty($upgrades->all())) {
            $upgrades = $this->parent()->upgrades;
            $included =  $this->parent();
        }
        $disabled = $included->available_stock <= $required-1;
        $keys[0] = ['name' => 'Included - ' . ($disabled ? 'Out of Stock' : StringFormatter::formatCurrency(0)), 'disabled' => $disabled,];

        foreach ($upgrades as $upgrade) {
            $disabled = $upgrade->upgrade->available_stock <= $required-1;
            $keys[$upgrade->id] = ['name' => $upgrade->description . ' - ' . ($disabled ? 'Out of Stock' : StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price)), 'disabled' => $disabled,];
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
        return OrderActivity::create([
            'order_customer_id' => $orderCustomer->id,
            'activity_inventory_tour_id' => $this->id,
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
