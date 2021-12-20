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

    protected $fillable = ['activity_type_id', 'address_id', 'name', 'description', 'currency_id', 'notes','image_url'];
    protected $cascadeDeletes = ['activityInventory'];

    public static function getValidationRules()
    {
        return [
            'activity_type_id' => 'required|exists:activity_types,id',
            'name' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public function activityInventory()
    {
        return $this->hasMany(ActivityInventory::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
