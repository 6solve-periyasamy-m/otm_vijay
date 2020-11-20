<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransportInventory extends Model
{
    use HasFactory;

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
        $transport = Transport::where('id', $this->transport_id)->first();
        $departure_location = Location::where('id', $transport->departure_location_id)->first();
        $arrival_location = Location::where('id', $transport->arrival_location_id)->first();
        $departure_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->departure_date_time)->format('d/m/Y H:i');
        $arrival_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $this->arrival_date_time)->format('d/m/Y H:i');

        return "{$transport->name}｜Departs from: {$departure_location->location_name} - Arrives at: {$arrival_location->location_name}｜Departs: {$departure_date_time} - Arrives: {$arrival_date_time}｜Travel Class: {$this->travelClass->title}";
    }

    public $additional_attributes = ['Transport_for_tour'];
}
