<?php

namespace App\Models\Order;

use App\Models\Customer;
use App\Models\Group;
use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\OrderActivity;
use App\Models\OrderCustomerGroup;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use App\Repository\GroupRepository;
use App\Repository\OrderRepository;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Order\OrderCustomer
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $customer_id
 * @property string|null $tour_cost Cost for included components for the customer
 * @property string|null $single_occupancy_surcharge Single occupancy surcharge for the customer
 * @property string|null $travel_insurer Travel insurer for the customer
 * @property string|null $policy_number Policy number for the order
 * @property SupportCarbon|null $created_at
 * @property SupportCarbon|null $updated_at
 * @property SupportCarbon|null $deleted_at
 * @property-read SupportCollection|OrderCustomerAdjustment[] $adjustments Customer specific price adjustments
 * @property-read int|null $adjustments_count Amount of customer specific adjustments
 * @property-read Customer|null $customer The customer details
 * @property-read string $booking_reference The booking reference of the order
 * @property-read string $customer_name The full name of the customer
 * @property-read bool $has_occupancy Whether the customer has occupancy set correctly
 * @property-read bool $has_surcharge Whether the customer should be charged for single occupancy
 * @property-read bool $is_lead_booker Whether the customer is the lead booker
 * @property-read bool $cancelled Whether the customer is cancelled
 * @property-read string $lead_booker_name The full name of the lead booker
 * @property-read Carbon $ordered_on When the order was placed
 * @property-read Group|null $primary_group The primary group of the customer
 * @property-read SupportCollection|Group[] $groups All groups the customer is in
 * @property-read int|null $groups_count How many groups the customer is in
 * @property-read Order|null $order The order details
 * @property-read SupportCollection|OrderActivity[] $orderActivities What activities the customer is taking part in
 * @property-read int|null $order_activities_count How many activities the customer is taking part in
 * @property-read SupportCollection|OrderCustomerGroup[] $orderCustomerGroups Pivot list for OrderCustomerGroup
 * @property-read int|null $order_customer_groups_count Amount of pivots for customer
 * @property-read SupportCollection|OrderFlight[] $orderFlights What flights the customer is taking
 * @property-read int|null $order_flights_count How many flights the customer is taking
 * @property-read SupportCollection|OrderMerchandise[] $orderMerchandise What extras the customer has ordered
 * @property-read int|null $order_merchandise_count How many extras the customer has ordered
 * @property-read SupportCollection|OrderTransport[] $orderTransports What transport the customer is taking
 * @property-read int|null $order_transports_count How many transport the customer is taking
 * @method static Builder|OrderCustomer newModelQuery()
 * @method static Builder|OrderCustomer newQuery()
 * @method static QueryBuilder|OrderCustomer onlyTrashed()
 * @method static Builder|OrderCustomer query()
 * @method static Builder|OrderCustomer whereCreatedAt($value)
 * @method static Builder|OrderCustomer whereCustomerId($value)
 * @method static Builder|OrderCustomer whereDeletedAt($value)
 * @method static Builder|OrderCustomer whereId($value)
 * @method static Builder|OrderCustomer whereOrderId($value)
 * @method static Builder|OrderCustomer wherePolicyNumber($value)
 * @method static Builder|OrderCustomer whereSingleOccupancySurcharge($value)
 * @method static Builder|OrderCustomer whereTourCost($value)
 * @method static Builder|OrderCustomer whereTravelInsurer($value)
 * @method static Builder|OrderCustomer whereUpdatedAt($value)
 * @method static QueryBuilder|OrderCustomer withTrashed()
 * @method static QueryBuilder|OrderCustomer withoutTrashed()
 * @mixin Eloquent
 */
class OrderCustomer extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['order_id', 'customer_id', 'tour_cost', 'single_occupancy_surcharge', 'travel_insurer', 'policy_number',];
    protected array $cascadeDeletes = ['orderCustomerGroups', 'orderActivities', 'orderFlights', 'orderTransports', 'adjustments'];

    public static function getValidationRules(): array
    {
        return [
            'customer_id' => 'exists:customers,id',
            'tour_cost' => 'numeric',
            'single_occupancy_surcharge' => 'numeric',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderCustomerGroups(): HasMany
    {
        return $this->hasMany(OrderCustomerGroup::class, 'order_customer_id');
    }

    public function orderActivities(): HasMany
    {
        return $this->hasMany(OrderActivity::class, 'order_customer_id');
    }

    public function orderFlights(): HasMany
    {
        return $this->hasMany(OrderFlight::class, 'order_customer_id');
    }

    public function orderTransports(): HasMany
    {
        return $this->hasMany(OrderTransport::class, 'order_customer_id');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(OrderCustomerAdjustment::class, 'order_customer_id');
    }

    public function orderMerchandise(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'order_customer_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, OrderCustomerGroup::class)->using(OrderCustomerGroup::class);
    }

    public function orderAccommodation(): Collection
    {
        return GroupRepository::getOrderCustomerAccommodation($this);
    }

    public function getCancelledAttribute(): bool
    {
        return $this->order->cancelled;
    }

    public function getBookingReferenceAttribute(): string
    {
        return $this->order->booking_reference;
    }

    public function getOrderedOnAttribute(): Carbon
    {
        return $this->order->ordered_on;
    }

    public function getLeadBookerNameAttribute(): string
    {
        return "{$this->order->leadBooker->customer->first_name} {$this->order->leadBooker->customer->last_name}";
    }

    public function getCustomerNameAttribute(): string
    {
        return "{$this->customer->first_name} {$this->customer->last_name}";
    }

    public function getIsLeadBookerAttribute(): bool
    {
        return OrderRepository::isLeadBooker($this->order, $this->customer);
    }

    public function getPrimaryGroupAttribute(): ?Group
    {
        return $this->groups()->first();
    }

    public function getHasSurchargeAttribute(): bool
    {
        foreach ($this->groups as $group) {
            if ($group->orderCustomers()->count() == 1) return true;
        }
        return false;
    }

    public function getHasOccupancyAttribute(): bool
    {
        return OrderRepository::checkOccupancy($this);
    }
}
