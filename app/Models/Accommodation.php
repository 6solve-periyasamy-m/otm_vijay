<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Jahondust\ModelLog\Traits\ModelLogging;


class Accommodation extends Model
{
    //use ModelLogging;
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['region_id','name','description','audit_date','address','currency',];
    protected $cascadeDeletes = ['inventory'];
    const RULES = [
        'name' => 'required',
        'region_id' => 'required|exists:regions,id',
        'audit_date' => 'date',
        'currency' => 'size:3'
    ];

    public function orderAccommodation()
    {
        return $this->belongsTo(OrderAccommodation::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function board_type()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function getInventoryRelationAttribute()
    {
        return "{$this->title} | {$this->region->name}";
    }

    public function inventory() {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public $additional_attributes = ['inventory_relation'];
}
