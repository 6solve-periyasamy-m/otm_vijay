<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersAccommodation extends Model
{
    use HasFactory;

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }
    public function accommodation()
    {
        return $this->hasOne(Accommodation::class,'id','accommodation_id');
    }

    public function accommodationInventory()
    {
        return $this->hasOneThrough(AccommodationInventory::class, Accommodation::class, 'id', 'accommodation_id', 'accommodation_id');
    }

    public static function findByOrderCustomer($orderCustomerId)
    {
    $orderAccommodations = OrdersAccommodation::where('order_customer_id',$orderCustomerId)->get();

        return $orderAccommodations;
    }
}
