<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityInventoryTourUpgrade extends Model
{
    use HasFactory;

    public function base() {
        return $this->belongsTo(ActivityInventoryTour::class, 'base_id');
    }

    public function upgrade(){
        return $this->belongsTo(ActivityInventoryTour::class, 'upgrade_id');
    }
}
