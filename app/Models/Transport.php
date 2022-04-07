<?php

namespace App\Models;

use App\Models\Order\Component\OrderTransport;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['transport_type_id', 'operator_id', 'departure_address_id', 'arrival_address_id', 'name', 'description', 'currency_id', 'is_domestic', 'notes','image_url'];
    protected array $cascadeDeletes = ['transportInventory'];

    public static function getValidationRules(): array
    {
        return [
            'transport_type_id' => 'required|exists:transport_types,id',
            'operator_id' => 'required|exists:operators,id',
            'departure_address_id' => 'required|exists:addresses,id',
            'arrival_address_id' => 'required|exists:addresses,id',
            'name' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public function transportInventory(): HasMany
    {
        return $this->hasMany(TransportInventory::class, 'transport_id');
    }

    public function transportType(): BelongsTo
    {
        return $this->belongsTo(TransportType::class, 'transport_type_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function departureAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'departure_address_id');
    }

    public function arrivalAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'arrival_address_id');
    }

    public function getInventoryRelationAttribute(): string
    {
        $operator = !is_null($this->operator) ? $this->operator->name : "Not Set";
        $departureAddress = !is_null($this->departureAddress) ? $this->departureAddress->name : "Not Set";
        $arrivalAddress = !is_null($this->arrivalAddress) ? $this->arrivalAddress->name : "None";

        return "{$this->name} | Operator: {$operator} | Departs: {$departureAddress} | Arrives: {$arrivalAddress}";
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function __toString(): string
    {
        return "{$this->name} ({$this->transportType}) ({$this->departureAddress->name} to {$this->arrivalAddress->name}) ({$this->operator})";
    }
}
