<?php

namespace App\Models\Order\Component;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Order\Component\OrderActivityRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Component\OrderActivity
 *
 * @property int $id
 * @property int $order_customer_id
 * @property int $activity_inventory_tour_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property float|null $estimated_purchase_price
 * @property-read float $purchase_price
 * @property-read ActivityInventoryTour $activityInventoryTour
 * @property-read Activity $activity
 * @property-read ActivityInventory $activity_inventory
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read OrderCustomer $orderCustomer
 * @property-read ActivityInventoryTour $tourComponent
 * @property-read OrderActivityRepository $repository The repository used for calculations and storage
 * @method static Builder|OrderActivity newModelQuery()
 * @method static Builder|OrderActivity newQuery()
 * @method static QueryBuilder|OrderActivity onlyTrashed()
 * @method static Builder|OrderActivity query()
 * @method static Builder|OrderActivity whereActivityInventoryTourId($value)
 * @method static Builder|OrderActivity whereCost($value)
 * @method static Builder|OrderActivity whereCreatedAt($value)
 * @method static Builder|OrderActivity whereDeletedAt($value)
 * @method static Builder|OrderActivity whereId($value)
 * @method static Builder|OrderActivity whereOrderCustomerId($value)
 * @method static Builder|OrderActivity whereUpdatedAt($value)
 * @method static QueryBuilder|OrderActivity withTrashed()
 * @method static QueryBuilder|OrderActivity withoutTrashed()
 * @mixin Eloquent
 */
class OrderActivity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double',];

    private OrderActivityRepository $internal_repository;

    public static function findByOrderCustomer($orderCustomerId): Collection|array
    {
        return self::where('order_customer_id', $orderCustomerId)->get();
    }

    public static function compare(OrderActivity $a, OrderActivity $b): int
    {
        $aStart = $a->tourComponent->inventory->starts_at;
        $bStart = $b->tourComponent->inventory->starts_at;
        if ($aStart?->gt($bStart)) return 1;
        if ($aStart?->lt($bStart)) return -1;
        return 0;
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public function activityInventoryTour(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public function getActivityInventoryAttribute(): ActivityInventory
    {
        return $this->activityInventoryTour->activityInventory;
    }

    public function getActivityAttribute(): Activity
    {
        return $this->activityInventoryTour->activityInventory->activity;
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function getDetailsAttribute(): string
    {
        return (string)($this->tourComponent->inventory);
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price ?? 0.0;
    }

    public function getRepositoryAttribute(): OrderActivityRepository
    {
        if (!isset ($this->internal_repository)) $this->internal_repository = new OrderActivityRepository($this);
        return $this->internal_repository;
    }

    public function swap(ActivityInventoryTour $swap): void
    {
        $this->activity_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }

    public function getPurchasePriceAttribute(): float
    {
        $inventory = $this->tourComponent->inventory;
        return $this->estimated_purchase_price ?? $inventory->local_purchase_price ?? 0.0;
    }
}
