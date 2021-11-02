<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['transport_type_id','operator_id','departure_location_id','arrival_location_id','name','description','currency','is_domestic','notes',];
    protected $cascadeDeletes = ['transportInventory'];
    const RULES = [
        'transport_type_id' => 'required|exists:transport_types,id',
        'operator_id' => 'required|exists:operators,id',
        'departure_location_id' => 'required|exists:locations,id',
        'arrival_location_id' => 'required|exists:locations,id',
        'name' => 'required',
    ];

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

    public static function findDepartureLocation(OrderTransport $ordersTransport)
    {
       return Location::where('id', $ordersTransport->transport->departure_location_id);
    }

    public function getInventoryRelationAttribute()
    {
        $operator = !is_null($this->operator) ? $this->operator->name : "Not Set";
        $departureLocation = !is_null($this->departureLocation) ? $this->departureLocation->name : "Not Set";
        $arrivalLocation = !is_null($this->arrivalLocation) ? $this->arrivalLocation->name : "None";

        return "{$this->name} | Operator: {$operator} | Departs: {$departureLocation} | Arrives: {$arrivalLocation}";
    }

    public $additional_attributes = ['inventory_relation'];
}
