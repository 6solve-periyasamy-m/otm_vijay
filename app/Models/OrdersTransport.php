<?php

namespace App\Models;

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
        $transportInventoryTour = $this->transportInventoryTour()->first();
        if ($transportInventoryTour == null) return null;
        $transportInventory = $transportInventoryTour->transportInventory()->first();
        if ($transportInventory == null) return null;
        return $transportInventory->transport();
    }

    public function transportInventory()
    {
        $transportInventoryTour = $this->transportInventoryTour()->first();
        if ($transportInventoryTour == null) return null;
        return $transportInventoryTour->transportInventory();
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
