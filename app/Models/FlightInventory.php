<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightInventory extends Model
{
    use SoftDeletes;

    public $additional_attributes = ['flight_for_tour'];

    protected $fillable = ['flight_id','travel_class_id','flight_number','check_in_date_time','departure_date_time','arrival_date_time','fit_selectable','stock','purchase_price','sales_price','currency','notes',];

    const RULES = [
        'travel_class_id' => 'required|exists:travel_classes,id',
        'flight_number' => 'required',
        'check_in_date_time' => 'date',
        'departure_date_time' => 'date',
        'arrival_date_time' => 'date',
        'fit_selectable' => 'required',
        'stock' => 'required|numeric|integer',
        'purchase_price' => 'required|numeric',
        'sales_price' => 'required|numeric',
        'currency' => 'required|size:3',
    ];

    protected $casts = [
        'check_in_date_time' => 'datetime',
        'departure_date_time' => 'datetime',
        'arrival_date_time' => 'datetime',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function travelClass()
    {
        return $this->belongsTo(TravelClass::class);
    }

    public function component_type()
    {
        return $this->hasOneThrough(TourComponentType::class, FlightInventoryTour::class, 'flight_inventory_id', 'id', 'id');
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'flight_inventory_tour')->withPivot('sales_price', 'flight_type');
    }

    public function flightInventoryTour()
    {
        return $this->hasMany(FlightInventoryTour::class);
    }


    public function departureAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport()
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public static function findByTour($tour_id)
    {
        return FlightInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function getDepartureAirport()
    {
        return Airport::findOrFail($this->flight->departure_airport_id);
    }

    public function getArrivalAirport()
    {
        return Airport::findOrFail($this->flight->arrival_airport_id);
    }

    public function getFlightForTourAttribute()
    {
        $departure_airport = $this->getDepartureAirport()->name; //Airport::getAirportById($this->flight->departure_airport_id);
        $arrival_airport = $this->getArrivalAirport()->name; //Airport::getAirportById($this->flight->arrival_airport_id);

        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->departure_date_time)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->arrival_date_time)->format('d/m/Y H:i');

        $travel_class = is_null($this->travelClass) ? "" : "｜Travel Class: {$this->travelClass->title}";

        return "{$this->flight->airline->name}｜Departs from: {$departure_airport} - Arrives at: {$arrival_airport}｜Departs: {$departure_date} - Arrives: {$arrival_date}{$travel_class}";
    }

    // public function getFlightDetails()
    // {
    //     return "{$this->flight->airline->name} | Departs from: {$this->getDepartureAirport()->location->name} - Arrives at: {$this->getArrivalAirport()->location->name} ";
    // }
}
