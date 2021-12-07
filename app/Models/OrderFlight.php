<?php

namespace App\Models;

use App\Repository\FlightComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFlight extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'flight_inventory_tour_id'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderFlights = OrderFlight::where('order_customer_id', $orderCustomerId)->with('arrivalAirport')->with('departureAirport')->get();

        return $orderFlights;
    }

    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class);
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
}
