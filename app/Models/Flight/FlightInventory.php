<?php

namespace App\Models\Flight;

use App\Models\Order\Component\OrderFlight;
use App\Models\Supplier\SupplierContractComponent;
use App\Models\TravelClass;
use App\Repository\Model\Flight\FlightInventoryRepository;
use Database\Factories\Flight\FlightInventoryFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Flight\FlightInventory
 *
 * @property int $id
 * @property int $flight_id
 * @property int $travel_class_id
 * @property Carbon|null $check_in
 * @property Carbon|null $departs_at
 * @property Carbon|null $arrives_at
 * @property string $flight_number
 * @property bool $fit_selectable
 * @property int|null $stock
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $contracted Amount of contracted stock
 * @property-read float $local_purchase_price FX Converted Purchase Price
 * @property-read Airport|null $arrivalAirport
 * @property-read Flight $component
 * @property-read Airport|null $departureAirport
 * @property-read Flight $flight
 * @property-read Collection|SupplierContractComponent[] $contractComponents
 * @property-read Collection|FlightInventoryTour[] $flightInventoryTour
 * @property-read Collection|OrderFlight[] $orders
 * @property-read int|null $flight_inventory_tour_count
 * @property-read string $flight_for_tour
 * @property-read int $used_on_tour_count
 * @property-read int $used_stock How much stock has been sold
 * @property-read Collection|FlightInventoryTour[] $tourComponents
 * @property-read int|null $tour_components_count
 * @property-read TravelClass $travelClass
 * @property-read FlightInventoryRepository $repository
 * @method static FlightInventoryFactory factory(...$parameters)
 * @method static Builder|FlightInventory newModelQuery()
 * @method static Builder|FlightInventory newQuery()
 * @method static QueryBuilder|FlightInventory onlyTrashed()
 * @method static Builder|FlightInventory query()
 * @method static Builder|FlightInventory whereArrivesAt($value)
 * @method static Builder|FlightInventory whereCheckIn($value)
 * @method static Builder|FlightInventory whereCreatedAt($value)
 * @method static Builder|FlightInventory whereDeletedAt($value)
 * @method static Builder|FlightInventory whereDepartsAt($value)
 * @method static Builder|FlightInventory whereFitSelectable($value)
 * @method static Builder|FlightInventory whereFlightId($value)
 * @method static Builder|FlightInventory whereFlightNumber($value)
 * @method static Builder|FlightInventory whereId($value)
 * @method static Builder|FlightInventory whereNotes($value)
 * @method static Builder|FlightInventory wherePurchasePrice($value)
 * @method static Builder|FlightInventory whereSalesPrice($value)
 * @method static Builder|FlightInventory whereStock($value)
 * @method static Builder|FlightInventory whereTravelClassId($value)
 * @method static Builder|FlightInventory whereUpdatedAt($value)
 * @method static QueryBuilder|FlightInventory withTrashed()
 * @method static QueryBuilder|FlightInventory withoutTrashed()
 * @mixin Eloquent
 */
class FlightInventory extends Model
{
    use SoftDeletes, CascadeSoftDeletes, HasFactory;

    protected array $cascadeDeletes = ['flightInventoryTour'];
    protected $guarded = [];
    protected $casts = [
        'check_in' => 'datetime',
        'departs_at' => 'datetime',
        'arrives_at' => 'datetime',
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];

    private FlightInventoryRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'travel_class_id' => 'required|exists:travel_classes,id',
            'flight_number' => 'required',
            'check_in' => 'date',
            'departs_at' => 'date',
            'arrives_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'nullable|numeric',
            'sales_price' => 'nullable|numeric',
        ];
    }

    public static function findByTour($tour_id): Collection|array
    {
        return FlightInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->get();
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function contractComponents(): MorphMany
    {
        return $this->morphMany(SupplierContractComponent::class, 'component');
    }

    public function travelClass(): BelongsTo
    {
        return $this->belongsTo(TravelClass::class, 'travel_class_id');
    }

    public function flightInventoryTour(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'flight_inventory_id');
    }

    public function departureAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public function getFlightForTourAttribute(): string
    {
        $departure_airport = $this->departureAirport->name;
        $arrival_airport = $this->arrivalAirport->name;

        $departure_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->departs_at)->format('d/m/Y H:i');
        $arrival_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->arrives_at)->format('d/m/Y H:i');

        $travel_class = is_null($this->travelClass) ? "" : "｜Travel Class: {$this->travelClass->name}";

        return "{$this->flight->airline->name}｜Departs from: {$departure_airport} - Arrives at: {$arrival_airport}｜Departs: {$departure_date} - Arrives: {$arrival_date}{$travel_class}";
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function getContractedAttribute(): int
    {
        return $this->contractComponents()->sum('quantity');
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(FlightInventoryTour::class, 'flight_inventory_id');
    }

    public function __toString(): string
    {
        return "{$this->component} - {$this->flight_number} ({$this->travelClass}) (" . f_datetime($this->departs_at) . " to " . f_datetime($this->arrives_at) . ")";
    }

    public function getRepositoryAttribute(): FlightInventoryRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new FlightInventoryRepository($this);
        return $this->internal_repository;
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(OrderFlight::class, FlightInventoryTour::class, 'flight_inventory_id', 'flight_inventory_tour_id');
    }

    public function getLocalPurchasePriceAttribute(): float
    {
        return fx_convert($this->purchase_price, $this->component->currency);
    }
}
