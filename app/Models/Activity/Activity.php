<?php

namespace App\Models\Activity;

use App\Models\ActivityType;
use App\Models\Address;
use App\Models\Currency;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Activity extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected $fillable = ['activity_type_id', 'address_id', 'name', 'description', 'currency_id', 'notes','image_url'];
    protected array $cascadeDeletes = ['activityInventory'];

    public static function getValidationRules(): array
    {
        return [
            'activity_type_id' => 'required|exists:activity_types,id',
            'name' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public function activityInventory(): HasMany
    {
        return $this->hasMany(ActivityInventory::class, 'activity_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function __toString(): string
    {

        return "{$this->name} ({$this->activityType}) ({$this->address->region}, {$this->address->country})";
    }
}
