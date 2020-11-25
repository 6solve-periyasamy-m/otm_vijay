<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransportInventory extends Model
{
    use HasFactory;

    protected $casts = [
        "departure_date_time" => "datetime",
        "arrival_date_time" => "datetime"
    ];

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class);
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
    }

    public function getTransportForTourAttribute()
    {
        $departure_location = Location::getLocationById($this->transport->departure_location_id);
        $arrival_location = Location::getLocationById($this->transport->arrival_location_id);

        $departure_date_time =  $this->departure_date_time->format('d/m/Y H:i');
        $arrival_date_time = $this->arrival_date_time->format('d/m/Y H:i');

        return "{$this->transport->name}｜Departs from: {$departure_location->location_name} - Arrives at: {$arrival_location->location_name}｜Departs: {$departure_date_time} - Arrives: {$arrival_date_time}｜Travel Class: {$this->travelClass->title}";
    }

    public $additional_attributes = ['Transport_for_tour'];
}
