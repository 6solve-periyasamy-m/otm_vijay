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

    protected $fillable = ['order_customer_id', 'accommodation_inventory_tour_id','cost'];
    public $additional_attributes = ['details','tour_component_type','tour_sales_price'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderAccommodations = OrderAccommodation::where('order_customer_id', $orderCustomerId)->get();

        return $orderAccommodations;
    }

    // TODO: Deprecate
    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
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

    public function isCancelled(): bool
    {
        return $this->orderCustomers->order->cancelled;
    }

    public function tourComponent()
    {
        return $this->accommodationInventoryTour();
    }

    public function getDetailsAttribute()
    {
        return "{$this->tourComponent->inventory} - {$this->tourComponent->booking_policy}";
    }

    public function getTourComponentTypeAttribute()
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute()
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function orderCustomer()
    {
        return $this->orderCustomers();
    }
}
