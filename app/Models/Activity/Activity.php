<?php

namespace App\Models\Activity;

use App\Models\Location\Address;
use App\Models\Location\Currency;
use App\Models\Order\Component\OrderActivity;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Activity\ActivityRepository;
use Database\Factories\Activity\ActivityFactory;
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
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;


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
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|ActivityInventory[] $activityInventory
 * @property-read Collection|OrderActivity[] $orders
 * @property-read int|null $activity_inventory_count
 * @property-read ActivityType $activityType
 * @property-read Address $address
 * @property-read ActivityRepository $repository
 * @property-read Currency|null $currency
 * @method static ActivityFactory factory(...$parameters)
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
    use SoftDeletes, CascadeSoftDeletes, HasFactory, HasRelationships, HasRepository;

    protected $guarded = [];
    protected array $cascadeDeletes = ['activityInventory'];

    public static function getValidationRules(): array
    {
        return [
            'activity_type_id' => 'required|exists:activity_types,id',
            'name' => 'required',
            'image' => 'nullable|image',
            'address_name' => 'required_unless:use_existing,on',
            'address_id' => 'required_if:use_existing,on'
        ];
    }

    public function activityInventory(): HasMany
    {
        return $this->hasMany(ActivityInventory::class, 'activity_id');
    }

    public function orders(): HasManyDeep
    {
        return $this->hasManyDeep(OrderActivity::class, [ActivityInventory::class, ActivityInventoryTour::class]);
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
