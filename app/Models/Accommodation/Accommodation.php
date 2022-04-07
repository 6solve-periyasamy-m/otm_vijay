<?php

namespace App\Models\Accommodation;

use App\Models\Address;
use App\Models\Currency;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Accommodation extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'description', 'audit_date', 'address_id', 'currency_id','image_url'];
    protected array $cascadeDeletes = ['inventory'];
    protected $casts = ['audit_date' => 'date',];

    public static function getValidationRules(): array
    {
        return [
            'name' => 'required',
            'audit_date' => 'date',
            'currency_id' => 'nullable|exists:currencies,id',
            'image' => 'nullable|image',
        ];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function getInventoryRelationAttribute(): string
    {
        return "{$this->title} | {$this->address->name}";
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function __toString(): string
    {
        return "{$this->name} ({$this->address->region}, {$this->address->country})";
    }
}
