<?php

namespace App\Models\Order\Component;

use App\Models\Order\OrderCustomer;
use App\Models\TransportInventoryTour;
use App\Repository\TransportComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTransport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'transport_inventory_tour_id','cost'];
    public $additional_attributes = ['details','tour_component_type','tour_sales_price'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderTransports = OrderTransport::where('order_customer_id', $orderCustomerId)->get();

        return $orderTransports;
    }

    // TODO: Deprecate
    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function transport()
    {
        return TransportComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function transportInventory()
    {
        return TransportComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function transportInventoryTour()
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomers->order->cancelled;
    }

    public function tourComponent()
    {
        return $this->transportInventoryTour();
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

    public function swap(TransportInventoryTour $swap) {
        $this->transport_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }
}
