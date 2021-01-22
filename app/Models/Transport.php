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

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

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
    }

    public function getInventoryRelationAttribute()
    {
        return "{$this->name} | Operator: {$this->operator->operator_name} | Departs: {$this->departureLocation->location_name} | Arrives: {$this->arrivalLocation->location_name}";
    }

    public $additional_attributes = ['inventory_relation'];
}
