<?php

namespace App\Models\Order\Component;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Customer\Group;
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
 * App\Models\Order\Component\OrderAccommodation
 *
 * @property int $id
 * @property int $group_id
 * @property int $accommodation_inventory_tour_id
 * @property float $cost How much the component was sold for
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AccommodationInventoryTour $accommodationInventoryTour
 * @property-read Accommodation $accommodation
 * @property-read AccommodationInventory $accommodation_inventory
 * @property-read bool $cancelled Is the order cancelled
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read Group $group
 * @property-read AccommodationInventoryTour $tourComponent
 * @method static Builder|OrderAccommodation newModelQuery()
 * @method static Builder|OrderAccommodation newQuery()
 * @method static QueryBuilder|OrderAccommodation onlyTrashed()
 * @method static Builder|OrderAccommodation query()
 * @method static Builder|OrderAccommodation whereAccommodationInventoryTourId($value)
 * @method static Builder|OrderAccommodation whereCost($value)
 * @method static Builder|OrderAccommodation whereCreatedAt($value)
 * @method static Builder|OrderAccommodation whereDeletedAt($value)
 * @method static Builder|OrderAccommodation whereGroupId($value)
 * @method static Builder|OrderAccommodation whereId($value)
 * @method static Builder|OrderAccommodation whereUpdatedAt($value)
 * @method static QueryBuilder|OrderAccommodation withTrashed()
 * @method static QueryBuilder|OrderAccommodation withoutTrashed()
 * @mixin Eloquent
 */
class OrderAccommodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'accommodation_inventory_tour_id', 'cost', 'group_id'];
    protected $casts = ['cost' => 'double',];

    public static function findByOrderCustomer($orderCustomerId): Collection|array
    {
        return OrderAccommodation::where('order_customer_id', $orderCustomerId)->get();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function accommodationInventoryTour(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }

    public function getAccommodationInventoryAttribute(): AccommodationInventory
    {
        return $this->accommodationInventoryTour->accommodationInventory;
    }

    public function getAccommodationAttribute(): Accommodation
    {
        return $this->accommodationInventoryTour->accommodationInventory->accommodation;
    }

    public function getCancelledAttribute(): bool
    {
        // TODO: Fix when cross-order room sharing implemented
        foreach ($this->group->orderCustomers as $orderCustomer) {
            return $orderCustomer->order->cancelled;
        }
        // Assume the order is cancelled if the group has no customers
        return true;
    }


    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent->inventory} - {$this->tourComponent->booking_policy}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function swap(AccommodationInventoryTour $swap)
    {
        $this->accommodation_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }

    public static function compare(OrderAccommodation $a, OrderAccommodation $b): int
    {
        $aStart = $a->tourComponent->inventory->check_in;
        $bStart = $b->tourComponent->inventory->check_in;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }
}
