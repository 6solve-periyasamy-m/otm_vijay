<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlightInventory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightInventoryTour extends Model
{
    protected $table = 'flight_inventory_tour';
    public $additional_attributes = ['flight_inventory_for_tour'];
    use HasFactory;
    use SoftDeletes;

    public function flightInventory() 
    {
        return $this->belongsTo(FlightInventory::class);
    }

    public function getFlightInventoryForTourAttribute() 
    {
        return "{$this->flight_type} {$this->flight->flight_number}";
    }

}
