<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tour extends Model
{
    public function flightinventory()
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
}
