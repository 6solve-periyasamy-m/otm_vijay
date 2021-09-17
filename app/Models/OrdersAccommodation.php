<?php

namespace App\Models;

use App\Repository\AccommodationComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersAccommodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'accommodation_inventory_tour_id'];

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function accommodation()
    {
        return AccommodationComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function accommodationInventory()
    {
        return AccommodationComponentRepository::getInventoryFromOrderComponent($this->id);
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
