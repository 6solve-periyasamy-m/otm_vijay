<?php

namespace App\Models\Activity;

use App\Models\ActivityType;
use App\Models\Address;
use App\Models\Currency;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\Activity\Activity
 *
 * @property int $id
 * @property int $activity_type_id
 * @property string|null $description
 * @property string|null $image_url Asset url for the activity image
 * @property int $address_id
 * @property int|null $currency_id
 * @property string|null $name
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|ActivityInventory[] $activityInventory
 * @property-read int|null $activity_inventory_count
 * @property-read ActivityType $activityType
 * @property-read Address $address
 * @property-read Currency|null $currency
 * @method static Builder|Activity newModelQuery()
 * @method static Builder|Activity newQuery()
 * @method static QueryBuilder|Activity onlyTrashed()
 * @method static Builder|Activity query()
 * @method static Builder|Activity whereActivityTypeId($value)
 * @method static Builder|Activity whereAddressId($value)
 * @method static Builder|Activity whereCreatedAt($value)
 * @method static Builder|Activity whereCurrencyId($value)
 * @method static Builder|Activity whereDeletedAt($value)
 * @method static Builder|Activity whereDescription($value)
 * @method static Builder|Activity whereId($value)
 * @method static Builder|Activity whereImageUrl($value)
 * @method static Builder|Activity whereName($value)
 * @method static Builder|Activity whereNotes($value)
 * @method static Builder|Activity whereUpdatedAt($value)
 * @method static QueryBuilder|Activity withTrashed()
 * @method static QueryBuilder|Activity withoutTrashed()
 * @mixin Eloquent
 */
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
