<?php

namespace App\Models\Flight;

use App\Models\Location\Currency;
use App\Models\Order\Component\OrderFlight;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Flight\FlightRepository;
use Database\Factories\Flight\FlightFactory;
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
 * App\Models\Flight\Flight
 *
 * @property int $id
 * @property int|null $airline_id
 * @property int|null $departure_airport_id
 * @property int|null $arrival_airport_id
 * @property bool $is_domestic
 * @property string|null $image_url
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property int|null $currency_id
 * @property Carbon|null $available_from
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Airline|null $airline
 * @property-read Airport|null $arrivalAirport
 * @property-read Currency|null $currency
 * @property-read Airport|null $departureAirport
 * @property-read Collection|FlightInventory[] $flightInventory
 * @property-read int|null $flight_inventory_count
 * @property-read string $flight_details
 * @property-read FlightRepository $repository
 * @method static FlightFactory factory(...$parameters)
 * @method static Builder|Flight newModelQuery()
 * @method static Builder|Flight newQuery()
 * @method static QueryBuilder|Flight onlyTrashed()
 * @method static Builder|Flight query()
 * @method static Builder|Flight whereAirlineId($value)
 * @method static Builder|Flight whereArrivalAirportId($value)
 * @method static Builder|Flight whereAvailableFrom($value)
 * @method static Builder|Flight whereCreatedAt($value)
 * @method static Builder|Flight whereCurrencyId($value)
 * @method static Builder|Flight whereDeletedAt($value)
 * @method static Builder|Flight whereDepartureAirportId($value)
 * @method static Builder|Flight whereId($value)
 * @method static Builder|Flight whereImageUrl($value)
 * @method static Builder|Flight whereIsDomestic($value)
 * @method static Builder|Flight whereNotes($value)
 * @method static Builder|Flight whereUpdatedAt($value)
 * @method static QueryBuilder|Flight withTrashed()
 * @method static QueryBuilder|Flight withoutTrashed()
 * @mixin Eloquent
 */
class Flight extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasRelationships, HasRepository;

    protected array $cascadeDeletes = ['flightInventory'];
    protected $guarded = [];
    protected $casts = ['available_from' => 'date', 'is_domestic' => 'boolean'];
    protected $with = ['departureAirport', 'airline', 'departureAirport.address', 'departureAirport.address.country', 'arrivalAirport', 'arrivalAirport.address', 'arrivalAirport.address.country'];

    public static function getValidationRules(): array
    {
        return [
            'airline_id' => 'required|exists:airlines,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id',
            'available_from' => 'date',
            'image' => 'nullable|image',
        ];
    }

    public static function findOrCreate(Airline $airline, Airport $departure, Airport $arrival, bool $isDomestic, Currency $currency, string $notes): Flight
    {
        $flight = self::where('airline_id', '=', $airline->id)
            ->where('departure_airport_id', '=', $departure->id)
            ->where('arrival_airport_id', '=', $arrival->id)
            ->where('is_domestic', '=', $isDomestic)->first();
        if ($flight == null) {
            $flight = Flight::create([
                'airline_id' => $airline->id,
                'departure_airport_id' => $departure->id,
                'arrival_airport_id' => $arrival->id,
                'is_domestic' => $isDomestic,
                'currency_id' => $currency->id,
                'notes' => $notes,
            ]);
        }
        return $flight;
    }

    public function flightInventory(): HasMany
    {
        return $this->hasMany(FlightInventory::class, 'flight_id');
    }

    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function orders(): HasManyDeep
    {
        return $this->hasManyDeep(OrderFlight::class, [FlightInventory::class, FlightInventoryTour::class]);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getFlightDetailsAttribute(): string
    {
        return "{$this->airline->name} | Departs from: {$this->departureAirport->address->name} - Arrives at: {$this->arrivalAirport->address->name}";
    }

    public function __toString(): string
    {
        return "{$this->airline} ({$this->departureAirport} to {$this->arrivalAirport})";
    }
}
