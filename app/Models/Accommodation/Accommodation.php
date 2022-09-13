<?php

namespace App\Models\Accommodation;

use App\Models\Location\Address;
use App\Models\Location\Currency;
use App\Models\Order\Component\OrderAccommodation;
use App\Repository\Model\Accommodation\AccommodationRepository;
use Database\Factories\Accommodation\AccommodationFactory;
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
 * App\Models\Accommodation\Accommodation
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $audit_date
 * @property string|null $image_url Asset link for image
 * @property int|null $currency_id
 * @property int $address_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Address $address
 * @property-read Currency|null $currency
 * @property-read AccommodationRepository $repository
 * @property-read Collection|AccommodationInventory[] $inventory List of inventory items for this accommodation
 * @property-read int|null $inventory_count
 * @method static AccommodationFactory factory(...$parameters)
 * @method static Builder|Accommodation newModelQuery()
 * @method static Builder|Accommodation newQuery()
 * @method static QueryBuilder|Accommodation onlyTrashed()
 * @method static Builder|Accommodation query()
 * @method static Builder|Accommodation whereAddressId($value)
 * @method static Builder|Accommodation whereAuditDate($value)
 * @method static Builder|Accommodation whereCreatedAt($value)
 * @method static Builder|Accommodation whereCurrencyId($value)
 * @method static Builder|Accommodation whereDeletedAt($value)
 * @method static Builder|Accommodation whereDescription($value)
 * @method static Builder|Accommodation whereId($value)
 * @method static Builder|Accommodation whereImageUrl($value)
 * @method static Builder|Accommodation whereName($value)
 * @method static Builder|Accommodation whereUpdatedAt($value)
 * @method static QueryBuilder|Accommodation withTrashed()
 * @method static QueryBuilder|Accommodation withoutTrashed()
 * @mixin Eloquent
 */
class Accommodation extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasRelationships;

    protected $fillable = ['name', 'description', 'audit_date', 'address_id', 'currency_id', 'image_url'];
    protected array $cascadeDeletes = ['inventory'];
    protected $casts = ['audit_date' => 'date',];

    private AccommodationRepository $internal_repository;

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

    public function inventory(): HasMany
    {
        return $this->hasMany(AccommodationInventory::class, 'accommodation_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function orderComponents(): HasManyDeep
    {
        return $this->hasManyDeep(OrderAccommodation::class, [AccommodationInventory::class, AccommodationInventoryTour::class], ['accommodation_id', 'accommodation_inventory_id', 'accommodation_inventory_tour_id']);
    }

    public function getRepositoryAttribute(): AccommodationRepository
    {
        isset($this->internal_repository) || $this->internal_repository = new AccommodationRepository($this);
        return $this->internal_repository;
    }

    public function __toString(): string
    {
        return $this->repository->__toString();
    }
}
