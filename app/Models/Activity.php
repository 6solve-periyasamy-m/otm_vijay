<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Activity extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    use HasFactory;

    protected $fillable = ['activity_type_id','location_id','title','description','notes',];
    protected $cascadeDeletes = ['activityInventory'];
    const RULES = [
        'activity_type_id' => 'required|exists:activity_types,id',
        'location_id' => 'required|exists:locations,id',
        'title' => 'required',
    ];

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
