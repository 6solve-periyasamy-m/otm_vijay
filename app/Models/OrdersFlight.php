<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersFlight extends Model
{
    use HasFactory;

    public function orderCustomers()
    {
        return $this->belongsTo(OrdersCustomer::class);
    }

    public function flight()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_id');
    }

    public function flightInventory()
    {
        return $this->hasOneThrough(FlightInventory::class, Flight::class, 'id', 'flight_id', 'flight_id');
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
