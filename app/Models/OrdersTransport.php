<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersTransport extends Model
{
    use HasFactory;


    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }
    public function transport()
    {
        return $this->hasOne(Transport::class,'id','transport_id');
    }

    public function transportInventory()
    {
        return $this->hasOneThrough(TransportInventory::class, Transport::class, 'id', 'transport_id', 'transport_id');
    }

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderTransports = OrdersTransport::where('order_customer_id',$orderCustomerId)->get();

        return $orderTransports;
    }
}
