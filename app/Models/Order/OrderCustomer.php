<?php

namespace App\Models\Order;

use App\Models\Customer;
use App\Models\Group;
use App\Models\OrderActivity;
use App\Models\OrderCustomerAdjustment;
use App\Models\OrderCustomerGroup;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use App\Repository\GroupRepository;
use App\Repository\OrderRepository;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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

    public function isCancelled(): bool
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
