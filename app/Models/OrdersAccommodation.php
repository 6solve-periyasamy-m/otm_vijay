<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersAccommodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function accommodation()
    {
        return $this->accommodationInventoryTour()->first()->accommodationInventory()->first()->accommodation();
    }

    public function accommodationInventory()
    {
        return $this->accommodationInventoryTour()->first()->accommodationInventory();
    }

    public function accommodationInventoryTour() {
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }


    public static function findByOrderCustomer($orderCustomerId)
    {
    $orderAccommodations = OrdersAccommodation::where('order_customer_id',$orderCustomerId)->get();

        return $orderAccommodations;
    }
}
