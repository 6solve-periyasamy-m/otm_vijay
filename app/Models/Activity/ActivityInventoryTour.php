<?php

namespace App\Models\Activity;

use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\ActivityComponentRepository;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use StringFormatter;

/**
 * App\Models\Activity\ActivityInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $activity_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ActivityInventory $activityInventory
 * @property-read int $available_stock
 * @property-read string $tour_name
 * @property-read ActivityInventory $inventory
 * @property-read Collection|OrderActivity[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read Collection|ActivityInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|ActivityInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @method static Builder|ActivityInventoryTour newModelQuery()
 * @method static Builder|ActivityInventoryTour newQuery()
 * @method static QueryBuilder|ActivityInventoryTour onlyTrashed()
 * @method static Builder|ActivityInventoryTour query()
 * @method static Builder|ActivityInventoryTour whereActivityInventoryId($value)
 * @method static Builder|ActivityInventoryTour whereCreatedAt($value)
 * @method static Builder|ActivityInventoryTour whereDeletedAt($value)
 * @method static Builder|ActivityInventoryTour whereId($value)
 * @method static Builder|ActivityInventoryTour whereTourComponentType($value)
 * @method static Builder|ActivityInventoryTour whereTourId($value)
 * @method static Builder|ActivityInventoryTour whereTourSalesPrice($value)
 * @method static Builder|ActivityInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|ActivityInventoryTour withTrashed()
 * @method static QueryBuilder|ActivityInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class ActivityInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
    protected $fillable = ['tour_id', 'activity_inventory_id', 'tour_component_type', 'tour_sales_price',];

    public static function getValidationRules(): array
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

    public function activityInventory(): BelongsTo
    {
        return $this->belongsTo(ActivityInventory::class, 'activity_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(ActivityInventory::class, 'activity_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderActivity::class, 'activity_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(ActivityInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(ActivityInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): ActivityInventoryTour
    {
        return ActivityComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->activityInventory;
        $component = $inventory->activity;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->starts_at) . ' to ' . StringFormatter::formatDateTime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
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

    public function addToOrder(OrderCustomer $orderCustomer): OrderActivity
    {
        return OrderActivity::create([
            'order_customer_id' => $orderCustomer->id,
            'activity_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }
}
