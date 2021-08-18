<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event');
    }
    public function paymentSchedule() {
        return $this->hasMany(PaymentSchedule::class, 'payment_schedule');
    }

    public function flightInventory()
    {
        return $this->belongsToMany(FlightInventory::class, 'flight_inventory_tour')->withPivot('sales_price', 'tour_component_type');
    }

    public function accommodationInventory()
    {
        return $this->belongsToMany(AccommodationInventory::class, 'accommodation_inventory_tours')->withPivot('sales_price', 'tour_component_type');
    }

    public function activityInventory()
    {
        return $this->belongsToMany(ActivityInventory::class, 'activity_inventory_tour')->withPivot('sales_price', 'tour_component_type');
    }

    public function transportInventory()
    {
        return $this->belongsToMany(TransportInventory::class, 'transport_inventory_tour')->withPivot('sales_price', 'tour_component_type');
    }

    // public function flightInventory()
    // {
    //     return $this->belongsTo(FlightInventory::class);
    // }

    // public function activityInventoryTour()
    // {
    //     return $this->belongsToMany(ActivityInventoryTour::class)->withPivot('created_at', 'deleted_at');
    // }
}
