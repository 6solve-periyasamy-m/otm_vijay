<?php

namespace App\Models;

use App\Repository\TransportComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersTransport extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function transport()
    {
        return TransportComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function transportInventory()
    {
        return TransportComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function transportInventoryTour() {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderTransports = OrdersTransport::where('order_customer_id',$orderCustomerId)->get();

        return $orderTransports;
    }
}
