<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_id','customer_id','tour_cost','single_occupancy_surcharge','travel_insurer','policy_number',];

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
        return $this->hasMany(OrdersAccommodation::class, 'order_customer_id');
    }

    public function orderActivities() {
        return $this->hasMany(OrdersActivity::class, 'order_customer_id');
    }

    public function orderFlights() {
        return $this->hasMany(OrdersFlight::class, 'order_customer_id');
    }

    public function orderTransports() {
        return $this->hasMany(OrdersTransport::class, 'order_customer_id');
    }

    public function adjustments() {
        return $this->hasMany(OrderCustomerAdjustment::class, 'order_customer_id');
    }
}
