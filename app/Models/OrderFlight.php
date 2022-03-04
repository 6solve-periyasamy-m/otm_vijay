<?php

namespace App\Models;

use App\Repository\FlightComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use StringFormatter;

class OrderFlight extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'flight_inventory_tour_id','cost'];
    public $additional_attributes = ['details','tour_component_type','tour_sales_price'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderFlights = OrderFlight::where('order_customer_id', $orderCustomerId)->with('arrivalAirport')->with('departureAirport')->get();

        return $orderFlights;
    }

    // TODO: Deprecate
    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function flight()
    {
        return FlightComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function flightInventory()
    {
        return FlightComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function flightInventoryTour()
    {
        return $this->belongsTo(FlightInventoryTour::class, 'flight_inventory_tour_id');
    }

    public function departureAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public function isCancelled(): bool
    {
        return $this->orderCustomers->order->cancelled;
    }

    public function tourComponent()
    {
        return $this->flightInventoryTour();
    }

    public function getDetailsAttribute()
    {
        return "{$this->tourComponent->inventory} - {$this->tourComponent->flight_type}";
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

    public function swap(FlightInventoryTour $swap) {
        $this->flight_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }

    public function getAtolStringAttribute(): string
    {
        return $this->tourComponent->atol_string;
    }
}
