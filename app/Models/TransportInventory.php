<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportInventory extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    public $additional_attributes = ['Transport_for_tour'];
    protected $fillable = ['transport_id', 'travel_class_id', 'departs_at', 'departure_time_confirmed', 'arrives_at', 'arrival_time_confirmed', 'fit_selectable', 'stock', 'purchase_price', 'sales_price', 'currency_id', 'notes',];
    protected $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        "departs_at" => "datetime",
        "arrives_at" => "datetime"
    ];

    public static function getValidationRules()
    {
        return [
            'travel_class_id' => 'required|exists:travel_classes,id',
            'departs_at' => 'date',
            'arrives_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',
        ];
    }

    public static function findByTour($tour_id)
    {
        return TransportInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('departureAddress', 'arrivalAddress')->get();
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'transport_inventory_tour')->withPivot('sales_price');
    }

    public function departureAddress()
    {
        return $this->hasOneThrough(Location::class, Transport::class, 'departure_location_id', 'id');
    }

    public function arrivalAddress()
    {
        return $this->hasOneThrough(Location::class, Transport::class, 'arrival_location_id', 'id');
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, TransportInventoryTour::class, 'transport_inventory_id', 'id', 'id');
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
    }

    public function tourComponents()
    {
        return $this->hasMany(TransportInventoryTour::class, 'transport_inventory_id');
    }

    public function getTransportForTourAttribute()
    {
        if (empty($this->transport)) {
            return 'not yet set';
        }

        $departure_location = Location::getLocationById($this->transport->departure_location_id);
        $arrival_location = Location::getLocationById($this->transport->arrival_location_id);

        $departs_at = $this->departs_at->format('d/m/Y H:i');
        $arrives_at = $this->arrives_at->format('d/m/Y H:i');

        return "{$this->transport->name}｜Departs from: {$departure_location->name} - Arrives at: {$arrival_location->name}｜Departs: {$departs_at} - Arrives: {$arrives_at}";
        // build server edit: remove transport travelClass
        //return "{$this->transport->name}｜Departs from: {$departure_location->name} - Arrives at: {$arrival_location->name}｜Departs: {$departs_at} - Arrives: {$arrives_at}｜Travel Class: {$this->travelClass->name}";
    }
}
