<?php

namespace App\Models\Flight;

use App\Models\Location\Address;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Flight\AirportRepository;
use Database\Factories\Flight\AirportFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\Airport
 *
 * @property int $id
 * @property string $name
 * @property int $address_id
 * @property string|null $iata_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AirportRepository $repository
 * @property-read Address $address
 * @property-read Collection|Flight[] $departingFlights
 * @property-read Collection|Flight[] $arrivingFlights
 * @property-read Collection|FlightInventory[] $flightInventory
 * @property-read int|null $flight_inventory_count
 * @method static AirportFactory factory(...$parameters)
 * @method static Builder|Airport newModelQuery()
 * @method static Builder|Airport newQuery()
 * @method static QueryBuilder|Airport onlyTrashed()
 * @method static Builder|Airport query()
 * @method static Builder|Airport whereAddressId($value)
 * @method static Builder|Airport whereCreatedAt($value)
 * @method static Builder|Airport whereDeletedAt($value)
 * @method static Builder|Airport whereIataCode($value)
 * @method static Builder|Airport whereId($value)
 * @method static Builder|Airport whereName($value)
 * @method static Builder|Airport whereUpdatedAt($value)
 * @method static QueryBuilder|Airport withTrashed()
 * @method static QueryBuilder|Airport withoutTrashed()
 * @mixin Eloquent
 */
class Airport extends Model
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name', 'iata_code', 'address_id'];

    public static function getValidationRules(): array
    {
        return [
            'name' => 'required|unique:airports,name',
            'iata_code' => 'required|size:3',
            'address_name' => 'required_unless:use_existing,on',
            'address_id' => 'required_if:use_existing,on'
        ];
    }

    public function flightInventory(): HasManyThrough
    {
        return $this->hasManyThrough(FlightInventory::class, Flight::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function departingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }

    public function __toString()
    {
        return $this->name . ' - ' . $this->iata_code . ' - ' . $this->address->country;
    }
}
