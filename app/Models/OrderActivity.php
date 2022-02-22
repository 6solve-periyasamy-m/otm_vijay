<?php

namespace App\Models;

use App\Repository\ActivityComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderActivity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'activity_inventory_tour_id','cost'];
    public $additional_attributes = ['details','tour_component_type','tour_sales_price'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderActivities = OrderActivity::where('order_customer_id', $orderCustomerId)->get();


        return $orderActivities;
    }

    // TODO: Deprecate
    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function activity()
    {
        return ActivityComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function activityInventory()
    {
        return ActivityComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function activityInventoryTour()
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public function isCancelled(): bool
    {
        return $this->orderCustomers->order->cancelled;
    }

    public function tourComponent()
    {
        return $this->activityInventoryTour();
    }

    public function getDetailsAttribute()
    {
        return "{$this->tourComponent->inventory}";
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

    public function applyUpgrade(AccommodationInventoryTourUpgrade $upgrade) {
        $this->activity_inventory_tour_id = $upgrade->upgrade_id;
        $this->cost = $upgrade->upgrade->tour_sales_price;
        $this->save();
    }
}
