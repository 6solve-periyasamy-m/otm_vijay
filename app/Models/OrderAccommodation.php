<?php

namespace App\Models;

use App\Repository\AccommodationComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAccommodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'accommodation_inventory_tour_id'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderAccommodations = OrderAccommodation::where('order_customer_id', $orderCustomerId)->get();

        return $orderAccommodations;
    }

    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class);
    }

    public function accommodation()
    {
        return AccommodationComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function accommodationInventory()
    {
        return AccommodationComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function accommodationInventoryTour()
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'accommodation_inventory_tour_id');
    }
}
