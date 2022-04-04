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
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomer extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['order_id', 'customer_id', 'tour_cost', 'single_occupancy_surcharge', 'travel_insurer', 'policy_number',];
    protected $cascadeDeletes = ['orderCustomerGroups', 'orderActivities', 'orderFlights', 'orderTransports', 'adjustments'];
    public $additional_attributes = ['booking_reference', 'ordered_on', 'lead_booker_name', 'is_lead_booker', 'customer_name'];

    public static function getValidationRules()
    {
        return [
            'customer_id' => 'exists:customers,id',
            'tour_cost' => 'numeric',
            'single_occupancy_surcharge' => 'numeric',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderCustomerGroups()
    {
        return $this->hasMany(OrderCustomerGroup::class, 'order_customer_id');
    }

    public function orderAccommodation()
    {
        return GroupRepository::getOrderCustomerAccommodation($this);
    }

    public function orderActivities()
    {
        return $this->hasMany(OrderActivity::class, 'order_customer_id');
    }

    public function orderFlights()
    {
        return $this->hasMany(OrderFlight::class, 'order_customer_id');
    }

    public function orderTransports()
    {
        return $this->hasMany(OrderTransport::class, 'order_customer_id');
    }

    public function adjustments()
    {
        return $this->hasMany(OrderCustomerAdjustment::class, 'order_customer_id');
    }

    public function orderMerchandise()
    {
        return $this->hasMany(OrderMerchandise::class, 'order_customer_id');
    }

    public function isCancelled(): bool
    {
        return $this->order->cancelled;
    }

    public function getBookingReferenceAttribute()
    {
        return $this->order->booking_reference;
    }

    public function getOrderedOnAttribute()
    {
        return $this->order->ordered_on;
    }

    public function getLeadBookerNameAttribute()
    {
        return "{$this->order->leadBooker->customer->first_name} {$this->order->leadBooker->customer->last_name}";
    }

    public function getCustomerNameAttribute()
    {
        return "{$this->customer->first_name} {$this->customer->last_name}";
    }

    public function getIsLeadBookerAttribute()
    {
        return OrderRepository::isLeadBooker($this->order, $this->customer);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, OrderCustomerGroup::class)->using(OrderCustomerGroup::class);
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
