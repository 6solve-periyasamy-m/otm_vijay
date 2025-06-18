<?php

namespace App\Models\Transport;

use App\Models\Location\Address;
use App\Models\Location\Currency;
use App\Models\Order\Component\OrderTransport;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Transport\TransportRepository;
use Database\Factories\Transport\TransportFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Models\Transport\Transport
 *
 * @property int $id
 * @property int $transport_type_id
 * @property int $operator_id
 * @property int $departure_address_id
 * @property int $arrival_address_id
 * @property bool $is_domestic
 * @property string $name
 * @property string|null $image_url Asset URL for the component image
 * @property string|null $description
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property int|null $currency_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Address|null $arrivalAddress
 * @property-read Currency|null $currency
 * @property-read Address|null $departureAddress
 * @property-read Operator $operator
 * @property-read Occupancy $occupancy
 * @property-read Collection|TransportInventory[] $transportInventory
 * @property-read int|null $transport_inventory_count
 * @property-read TransportType $transportType
 * @property-read TransportRepository $repository
 * @method static TransportFactory factory(...$parameters)
 * @method static Builder|Transport newModelQuery()
 * @method static Builder|Transport newQuery()
 * @method static QueryBuilder|Transport onlyTrashed()
 * @method static Builder|Transport query()
 * @method static Builder|Transport whereArrivalAddressId($value)
 * @method static Builder|Transport whereCreatedAt($value)
 * @method static Builder|Transport whereCurrencyId($value)
 * @method static Builder|Transport whereDeletedAt($value)
 * @method static Builder|Transport whereDepartureAddressId($value)
 * @method static Builder|Transport whereDescription($value)
 * @method static Builder|Transport whereId($value)
 * @method static Builder|Transport whereImageUrl($value)
 * @method static Builder|Transport whereIsDomestic($value)
 * @method static Builder|Transport whereName($value)
 * @method static Builder|Transport whereNotes($value)
 * @method static Builder|Transport whereOperatorId($value)
 * @method static Builder|Transport whereOccupancyId($value)
 * @method static Builder|Transport whereTransportTypeId($value)
 * @method static Builder|Transport whereUpdatedAt($value)
 * @method static QueryBuilder|Transport withTrashed()
 * @method static QueryBuilder|Transport withoutTrashed()
 * @mixin Eloquent
 */
class Transport extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasRelationships, HasRepository;

    protected $guarded = [];
    protected array $cascadeDeletes = ['transportInventory'];
    protected $casts = ['is_domestic' => 'boolean'];

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

    public function Occupancy(): BelongsTo
    {
        return $this->belongsTo(TransportOccupancy::class, 'transport_occupancy_id');
    }

    public function departureAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'departure_address_id');
    }

    public function arrivalAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'arrival_address_id');
    }


    public function arrivalTransferType(): string
    {
        return $this->transportType;
    }

    public function orders(): HasManyDeep
    {
        return $this->hasManyDeep(OrderTransport::class, [TransportInventory::class, TransportInventoryTour::class]);
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
