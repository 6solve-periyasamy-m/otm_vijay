<?php

namespace App\Models;

use App\Models\Order\OrderCustomer;
use App\Repository\AccommodationComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAccommodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'accommodation_inventory_tour_id','cost','group_id'];
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
        // TODO: Fix when cross-order room sharing implemented
        foreach ($this->group->orderCustomers as $orderCustomer) {
            return $orderCustomer->order->cancelled;
        }
        // Assume the order is cancelled if the group has no customers
        return true;
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

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function swap(AccommodationInventoryTour $swap) {
        $this->accommodation_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }
}
