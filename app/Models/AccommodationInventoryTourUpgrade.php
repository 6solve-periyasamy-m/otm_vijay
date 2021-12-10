<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationInventoryTourUpgrade extends Model
{
    use HasFactory;

    public function base() {
        return $this->belongsTo(AccommodationInventoryTour::class, 'base_id');
    }

    public function upgrade(){
        return $this->belongsTo(AccommodationInventoryTour::class, 'upgrade_id');
    }
}
