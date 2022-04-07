<?php

namespace App\Models;

use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;


class Airport extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'iata_code', 'address_id'];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:airports,name', 'iata_code' => 'required|size:3',];
    }

    public function flightInventory(): HasManyThrough
    {
        return $this->hasManyThrough(FlightInventory::class, Flight::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function flight(): HasMany
    {
        return $this->hasMany(Flight::class, 'airport_id');
    }

    public function __toString()
    {
        return $this->name . ' - ' . $this->iata_code . ' - ' . $this->address->country;
    }
}
