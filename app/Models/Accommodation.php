<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Jahondust\ModelLog\Traits\ModelLogging;


class Accommodation extends Model
{
    //use ModelLogging;
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;

    public $additional_attributes = ['inventory_relation'];
    protected $fillable = ['name', 'description', 'audit_date', 'address_id', 'currency_id','image_url'];
    protected $cascadeDeletes = ['inventory'];
    protected $casts = ['audit_date' => 'date',];

    public static function getValidationRules()
    {
        return [
            'name' => 'required',
            'audit_date' => 'date',
            'currency_id' => 'nullable|exists:currencies,id',
            'image' => 'nullable|image',
        ];
    }

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

    public function inventory()
    {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
