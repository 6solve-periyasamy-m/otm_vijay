<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightInventoryTourUpgrade extends Model
{
    use HasFactory;

    public function base() {
        return $this->belongsTo(FlightInventoryTour::class, 'base_id');
    }

    public function upgrade(){
        return $this->belongsTo(FlightInventoryTour::class, 'upgrade_id');
    }
}
