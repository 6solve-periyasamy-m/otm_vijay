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

    public $additional_attributes = ['inventory_relation'];
    protected $fillable = ['transport_type_id', 'operator_id', 'departure_address_id', 'arrival_address_id', 'name', 'description', 'currency_id', 'is_domestic', 'notes','image_url'];
    protected $cascadeDeletes = ['transportInventory'];

    public static function getValidationRules()
    {
        return [
            'transport_type_id' => 'required|exists:transport_types,id',
            'operator_id' => 'required|exists:operators,id',
            'departure_address_id' => 'required|exists:addresses,id',
            'arrival_address_id' => 'required|exists:addresses,id',
            'name' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public static function findDepartureAddress(OrderTransport $ordersTransport)
    {
        return Location::where('id', $ordersTransport->transport->departure_location_id);
    }

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

    public function departureAddress()
    {
        return $this->hasOne(Address::class, 'id', 'departure_address_id');
    }

    public function arrivalAddress()
    {
        return $this->hasOne(Address::class, 'id', 'arrival_address_id');
    }

    public function getInventoryRelationAttribute()
    {
        $operator = !is_null($this->operator) ? $this->operator->name : "Not Set";
        $departureAddress = !is_null($this->departureAddress) ? $this->departureAddress->name : "Not Set";
        $arrivalAddress = !is_null($this->arrivalAddress) ? $this->arrivalAddress->name : "None";

        return "{$this->name} | Operator: {$operator} | Departs: {$departureAddress} | Arrives: {$arrivalAddress}";
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
