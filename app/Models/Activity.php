<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Activity extends Model
{
    use SoftDeletes;

    public function activityInventory()
    {
        return $this->hasMany(ActivityInventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
}
