<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    public function transportInventory()
    {
        return $this->hasMany(TransportInventory::class);
    }

<<<<<<< HEAD
    public function location()
    {
        return $this->belongsTo(Location::class);
=======
    public function departureLocation()
    {
        return $this->hasOne(Location::class, 'id', 'departure_location_id');
    }

    public function arrivalLocation()
    {
        return $this->hasOne(Location::class, 'id', 'arrival_location_id');
    }

    public static function findDepartureLocation(OrdersTransport $ordersTransport)
    {
       return Location::where('id', $ordersTransport->transport->departure_location_id);
>>>>>>> add-orders
    }
}
