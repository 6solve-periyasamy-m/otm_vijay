<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersFlight extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function flight()
    {
        $flightInventoryTour = $this->flightInventoryTour()->first();
        if ($flightInventoryTour == null) return null;
        $flightInventory = $flightInventoryTour->flightInventory()->first();
        if ($flightInventory == null) return null;
        return $flightInventory->flight();
    }

    public function flightInventory()
    {
        $flightInventoryTour = $this->flightInventoryTour()->first();
        if ($flightInventoryTour == null) return null;
        return $flightInventoryTour->flightInventory();
    }

    public function flightInventoryTour() {
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

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderFlights = OrdersFlight::where('order_customer_id', $orderCustomerId)->with('arrivalAirport')->with('departureAirport')->get();

        return $orderFlights;
    }
}
