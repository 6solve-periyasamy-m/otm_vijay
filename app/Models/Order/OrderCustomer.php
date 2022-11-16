<?php

namespace App\Models\Order;

use App\Models\Customer\Customer;
use App\Models\Customer\Group;
use App\Models\Customer\OrderCustomerGroup;
use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Repository\Abstracts\OrderComponentRepository;
use App\Repository\Model\Order\OrderCustomerRepository;
use App\Repository\RoomingRepository;
use Carbon\Carbon;
use Database\Factories\Order\OrderCustomerFactory;
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
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Order\OrderCustomer
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $customer_id
 * @property float|null $tour_cost Cost for included components for the customer
 * @property float|null $single_occupancy_surcharge Single occupancy surcharge for the customer
 * @property string|null $travel_insurer Travel insurer for the customer
 * @property string|null $policy_number Policy number for the order
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property string|null $accommodation_notes
 * @property string|null $activity_notes
 * @property string|null $flight_notes
 * @property string|null $transport_notes
 * @property boolean $is_travelling
 * @property boolean $is_charged
 * @property SupportCarbon|null $created_at
 * @property SupportCarbon|null $updated_at
 * @property SupportCarbon|null $deleted_at
 * @property-read SupportCollection|OrderAccommodation[] $orderAccommodation
 * @property-read SupportCollection|OrderCustomerAdjustment[] $adjustments Customer specific price adjustments
 * @property-read int|null $adjustments_count Amount of customer specific adjustments
 * @property-read Customer|null $customer The customer details
 * @property-read string $booking_reference The booking reference of the order
 * @property-read string $customer_name The full name of the customer
 * @property-read string $tour_name The name of the tour the order is for
 * @property-read bool $has_occupancy Whether the customer has occupancy set correctly
 * @property-read bool $has_surcharge Whether the customer should be charged for single occupancy
 * @property-read bool $is_lead_booker Whether the customer is the lead booker
 * @property-read bool $cancelled Whether the customer is cancelled
 * @property-read bool $registered Is the traveller a registered user
 * @property-read string $lead_booker_name The full name of the lead booker
 * @property-read Carbon $ordered_on When the order was placed
 * @property-read float $adjustment_total The sum of all adjustments for the OrderCustomer
 * @property-read OrderCustomerRepository $repository The repository used for calculations and storage
 * @property-read OrderComponentRepository[] $components A generified list of order components
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
 * @method static OrderCustomerFactory factory(...$parameters)
 * @method static Builder|OrderCustomer newModelQuery()
 * @method static Builder|OrderCustomer newQuery()
 * @method static QueryBuilder|OrderCustomer onlyTrashed()
 * @method static Builder|OrderCustomer query()
 * @method static Builder|OrderCustomer whereAccommodationNotes($value)
 * @method static Builder|OrderCustomer whereActivityNotes($value)
 * @method static Builder|OrderCustomer whereCreatedAt($value)
 * @method static Builder|OrderCustomer whereCustomerId($value)
 * @method static Builder|OrderCustomer whereDeletedAt($value)
 * @method static Builder|OrderCustomer whereExternalNotes($value)
 * @method static Builder|OrderCustomer whereFlightNotes($value)
 * @method static Builder|OrderCustomer whereId($value)
 * @method static Builder|OrderCustomer whereInternalNotes($value)
 * @method static Builder|OrderCustomer whereOrderId($value)
 * @method static Builder|OrderCustomer wherePolicyNumber($value)
 * @method static Builder|OrderCustomer whereSingleOccupancySurcharge($value)
 * @method static Builder|OrderCustomer whereTourCost($value)
 * @method static Builder|OrderCustomer whereTransportNotes($value)
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
    use HasRelationships;

    protected $guarded = [];
    protected array $cascadeDeletes = ['orderCustomerGroups', 'orderActivities', 'orderFlights', 'orderTransports', 'adjustments'];
    protected $casts = ['tour_cost' => 'double', 'single_occupancy_surcharge' => 'double', 'is_charged' => 'boolean', 'is_travelling' => 'boolean'];
    private OrderCustomerRepository $internal_repository;

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

    public function orderMerchandise(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'order_customer_id');
    }

    public function orderAccommodation(): HasManyDeep
    {
        return $this->hasManyDeep(OrderAccommodation::class,
            [OrderCustomerGroup::class, Group::class],
            ['order_customer_id', 'id', 'group_id']
        );
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

    public function getTourNameAttribute(): string
    {
        return $this->order->tour->name;
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
        return $this->order->repository->isLeadBooker($this->customer);
    }

    public function getPrimaryGroupAttribute(): ?Group
    {
        return $this->groups()->first();
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, OrderCustomerGroup::class)->using(OrderCustomerGroup::class);
    }

    public function getHasSurchargeAttribute(): bool
    {
        $highest = $this->groups()->withCount('orderCustomers')->orderBy('order_customers_count', 'desc')->first();
        return isset($highest) && $highest->order_customers_count === 1;
    }

    public function getHasOccupancyAttribute(): bool
    {
        return RoomingRepository::checkOccupancy($this);
    }

    /**
     * @return float The sum of all adjustments for the OrderCustomer
     */
    public function getAdjustmentTotalAttribute(): float
    {
        return $this->adjustments()->sum('amount');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(OrderCustomerAdjustment::class, 'order_customer_id');
    }

    public function getRepositoryAttribute(): OrderCustomerRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new OrderCustomerRepository($this);
        return $this->internal_repository;
    }

    public function getComponentsAttribute(): array
    {
        return $this->repository->getComponents();
    }

    /**
     * @return array List of all additional costs for the order
     */
    public function getAdditionalCosts(): array
    {
        return $this->repository->getAdditionalCosts();
    }

    public function getRegisteredAttribute(): bool
    {
        return $this->customer?->registered ?? false;
    }
}
