<?php

namespace App\Models\Order\Component;

use App\Models\Activity;
use App\Models\ActivityInventory;
use App\Models\ActivityInventoryTour;
use App\Models\Order\OrderCustomer;
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
 * @property-read ActivityInventoryTour $activityInventoryTour
 * @property-read ActivityInventoryTour $tourComponent
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read OrderCustomer $orderCustomer
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

    protected $fillable = ['order_customer_id', 'activity_inventory_tour_id','cost'];

    public static function findByOrderCustomer($orderCustomerId): Collection|array
    {
        return OrderActivity::where('order_customer_id', $orderCustomerId)->get();
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

    public function activityInventory(): ActivityInventory
    {
        return $this->activityInventoryTour->activityInventory;
    }

    public function activity(): Activity
    {
        return $this->activityInventoryTour->activityInventory->activity;
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent->inventory}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function swap(ActivityInventoryTour $swap)
    {
        $this->activity_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }
}
