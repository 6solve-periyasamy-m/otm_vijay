<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityInventoryTourUpgrade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['upgrade_id','description'];

    public function base() {
        return $this->belongsTo(ActivityInventoryTour::class, 'base_id');
    }

    public function upgrade(){
        return $this->belongsTo(ActivityInventoryTour::class, 'upgrade_id');
    }
}
