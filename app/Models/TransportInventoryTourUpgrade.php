<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportInventoryTourUpgrade extends Model
{
    use HasFactory;

    public function base() {
        return $this->belongsTo(TransportInventoryTour::class, 'base_id');
    }

    public function upgrade(){
        return $this->belongsTo(TransportInventoryTour::class, 'upgrade_id');
    }
}
