<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomer extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['order_id', 'customer_id', 'tour_cost', 'single_occupancy_surcharge', 'travel_insurer', 'policy_number',];
    protected $cascadeDeletes = ['orderAccommodation', 'orderActivities', 'orderFlights', 'orderTransports', 'adjustments'];

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

    public function orderAccommodation()
    {
        return $this->hasMany(OrderAccommodation::class, 'order_customer_id');
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
}
