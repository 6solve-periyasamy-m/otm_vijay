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

    protected $fillable = ['name','description','audit_date','address_id','currency_id',];
    protected $cascadeDeletes = ['inventory'];
    const RULES = [
        'name' => 'required',
        'audit_date' => 'date',
        'currency' => 'size:3'
    ];

    public function orderAccommodation()
    {
        return $this->belongsTo(OrderAccommodation::class);
    }
    
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function board_type()
    {
        return $this->belongsTo(BoardType::class);
    }

    public function getInventoryRelationAttribute()
    {
        return "{$this->title} | {$this->address->name}";
    }

    public function inventory() {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public $additional_attributes = ['inventory_relation'];
}
