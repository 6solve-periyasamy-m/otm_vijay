<?php

namespace App\Models\Activity;

use App\Models\Booking\Component\BookingActivity;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
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

/**
 * App\Models\Activity\ActivityInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $activity_inventory_id
 * @property string $tour_component_type
 * @property float|null $tour_sales_price
 * @property bool $is_bookable
 * @property bool $stock_control_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $document_order
 * @property-read int $order
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
 * @method static Builder|ActivityInventoryTour whereIsBookable($value)
 * @method static Builder|ActivityInventoryTour whereStockControlActive($value)
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
    protected $guarded = [];
    protected $casts = ['tour_sales_price' => 'double', 'is_bookable' => 'boolean', 'stock_control_active' => 'boolean',];
    private ActivityInventoryTourRepository $internal_repository;

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

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingActivity::class, 'activity_inventory_tour_id');
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

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->activityInventory;
        $component = $inventory->activity;
        $dateString = "";
        if ($inventory->starts_at !== null && $inventory->ends_at !== null) {
            $dateString = " (" . f_datetime($inventory->starts_at) . " to " . f_datetime($inventory->ends_at) . ")";
        }
        return $component->name . "{$dateString} (" . $inventory->ticketType->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function parent(): ActivityInventoryTour
    {
        return $this->repository->getUpgradeParent();
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderActivity
    {
        return $this->repository->grantToCustomer($orderCustomer)->get();
    }

    public function getUsedTourStockAttribute(): int
    {
        return $this->repository->getUsedOnOrderCount();
    }

    public function getRepositoryAttribute(): ActivityInventoryTourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new ActivityInventoryTourRepository($this);
        return $this->internal_repository;
    }

    public function getOrderAttribute(): int
    {
        return $this->document_order ?? 0;
    }
}
