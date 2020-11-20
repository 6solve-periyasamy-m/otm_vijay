<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Activity extends Model
{
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
