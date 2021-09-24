<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function transportInventory()
    {
        return $this->hasMany(TransportInventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function transportType()
    {
        return $this->belongsTo(TransportType::class, 'transport_type_id');
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
        $operator = !is_null($this->operator) ? $this->operator->name : "Not Set";
        $departureLocation = !is_null($this->departureLocation) ? $this->departureLocation->location_name : "Not Set";
        $arrivalLocation = !is_null($this->arrivalLocation) ? $this->arrivalLocation->location_name : "None";

        return "{$this->name} | Operator: {$operator} | Departs: {$departureLocation} | Arrives: {$arrivalLocation}";
    }

    public $additional_attributes = ['inventory_relation'];
}
