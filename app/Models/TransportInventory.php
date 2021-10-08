<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportInventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['transport_id','travel_class_id','departure_date_time','departure_confirmed','arrival_date_time','arrival_confirmed','fit_selectable','stock','purchase_price','sales_price','currency','notes',];
    protected $casts = [
        "departure_date_time" => "datetime",
        "arrival_date_time" => "datetime"
    ];
    const RULES = [
        'travel_class_id' => 'required|exists:travel_classes,id',
        'departure_date_time' => 'date',
        'arrival_date_time' => 'date',
        'departure_confirmed' => 'required',
        'arrival_confirmed' => 'required',
        'fit_selectable' => 'required',
        'stock' => 'required|numeric|integer',
        'purchase_price' => 'required|numeric',
        'sales_price' => 'required|numeric',
        'currency' => 'required|size:3',
    ];

    public $additional_attributes = ['Transport_for_tour'];

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function tour()
    {
        return $this->belongsToMany(Tour::class, 'transport_inventory_tour')->withPivot('sales_price');
    }

    public function departureLocation()
    {
        return $this->hasOneThrough(Location::class, Transport::class, 'departure_location_id', 'id');
    }

    public function arrivalLocation()
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

    public function tourComponents() {
        return $this->hasMany(TransportInventoryTour::class, 'transport_inventory_id');
    }

    public static function findByTour($tour_id)
    {
        return TransportInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('departureLocation', 'arrivalLocation')->get();
    }

    public function getTransportForTourAttribute()
    {
        if (empty($this->transport)) {
            return 'not yet set';
        }
        
        $departure_location = Location::getLocationById($this->transport->departure_location_id);
        $arrival_location = Location::getLocationById($this->transport->arrival_location_id);
 
        $departure_date_time =  $this->departure_date_time->format('d/m/Y H:i');
        $arrival_date_time = $this->arrival_date_time->format('d/m/Y H:i');

        return "{$this->transport->name}｜Departs from: {$departure_location->name} - Arrives at: {$arrival_location->name}｜Departs: {$departure_date_time} - Arrives: {$arrival_date_time}";
	// build server edit: remove transport travelClass
        //return "{$this->transport->name}｜Departs from: {$departure_location->name} - Arrives at: {$arrival_location->name}｜Departs: {$departure_date_time} - Arrives: {$arrival_date_time}｜Travel Class: {$this->travelClass->title}";
    }
}
