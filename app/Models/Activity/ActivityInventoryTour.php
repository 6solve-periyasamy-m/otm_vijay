<?php

namespace App\Models\Activity;

use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\ActivityComponentRepository;
use App\Repository\Model\Activity\ActivityInventoryTourRepository;
use Database\Factories\Activity\ActivityInventoryTourFactory;
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
 * @property float|null $tour_sales_price
 * @property bool $is_bookable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ActivityInventory $activityInventory
 * @property-read int $available_stock
 * @property-read string $tour_name
 * @property-read int $used_tour_stock
 * @property-read ActivityInventory $inventory
 * @property-read Collection|OrderActivity[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read Collection|ActivityInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|ActivityInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @property-read ActivityInventoryTourRepository $repository
 * @method static ActivityInventoryTourFactory  factory(...$parameters)
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
    protected $casts = ['tour_sales_price' => 'double', 'is_bookable' => 'boolean',];

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
        return $component->name . ' (' . f_datetime($inventory->starts_at) . ' to ' . f_datetime($inventory->ends_at) . ') (' . $inventory->ticketType->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
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
            $keys[0] = 'Included - ' . f_currency(0);
        }
        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->available_stock <= $required-1) continue;
            $keys[$upgrade->id] = $upgrade->description . ' - ' . f_currency($upgrade->upgrade->tour_sales_price);
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
        if ($included->is_bookable) {
            $keys[0] = ['name' => 'Included - ' . ($disabled ? 'Out of Stock' : f_currency(0)), 'disabled' => $disabled,];
        }

        foreach ($upgrades as $upgrade) {
            if (!$upgrade->upgrade->is_bookable) continue;
            $disabled = $upgrade->upgrade->available_stock <= $required-1;
            $keys[$upgrade->id] = ['name' => $upgrade->description . ' - ' . ($disabled ? 'Out of Stock' : f_currency($upgrade->upgrade->tour_sales_price)), 'disabled' => $disabled,];
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
            if (!$upgrade->upgrade->is_bookable) continue;
            if ($upgrade->upgrade->available_stock <= 0) continue;
            if ($this->tour_component_type == 'Included' || $upgrade->upgrade->tour_sales_price >= $this->tour_sales_price) {
                $keys[$upgrade->id] = $upgrade->description . ' - ' . f_currency($upgrade->upgrade->tour_sales_price);
            }
        }
        return $keys;
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderActivity
    {
        return $this->repository->grantToCustomer($orderCustomer)->get();
    }

    public function getUsedTourStockAttribute(): int
    {
        $used = 0;
        foreach ($this->orders as $orderComponent) {
            if (!$orderComponent->isCancelled()) $used++;
        }
        return $used;
    }

    public function getRepositoryAttribute(): ActivityInventoryTourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new ActivityInventoryTourRepository($this);
        return $this->internal_repository;
    }
}
