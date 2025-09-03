<?php

namespace App\Models\Transport;

use App\Models\Order\Component\OrderTransport;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Supplier\SupplierContractComponent;
use App\Models\Tour\Tour;
use App\Models\TravelClass;
use App\Repository\Model\Transport\TransportInventoryRepository;
use Database\Factories\Transport\TransportInventoryFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use App\Models\Transport\TransportOccupancy;
use App\Models\User;

/**
 * App\Models\Transport\TransportInventory
 *
 * @property int $id
 * @property int $transport_id
 * @property int $travel_class_id
 * @property int $transport_occupancy_id
 * @property Carbon|null $departs_at
 * @property Carbon|null $arrives_at
 * @property bool $fit_selectable
 * @property int $stock
 * @property float $purchase_price
 * @property float $sales_price
 * @property string|null $transport_number
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property int $arrival_time_confirmed
 * @property int $departure_time_confirmed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $contracted Amount of contracted stock
 * @property-read float $local_purchase_price FX Converted Purchase Price
 * @property-read Transport $component
 * @property-read string $transport_for_tour
 * @property-read int $used_on_tour_count
 * @property-read Collection|SupplierContractComponent[] $contractComponents
 * @property-read int $used_stock How much stock has been sold
 * @property-read Collection|Tour[] $tour
 * @property-read int|null $tour_count
 * @property-read Collection|TransportInventoryTour[] $tourComponents
 * @property-read int|null $tour_components_count
 * @property-read Transport $transport
 * @property-read TravelClass $travelClass
 * @property-read TransportInventoryRepository $repository
 * @property-read TransportOccupancy $transportOccupancy
 * @method static TransportInventoryFactory factory(...$parameters)
 * @method static Builder|TransportInventory newModelQuery()
 * @method static Builder|TransportInventory newQuery()
 * @method static QueryBuilder|TransportInventory onlyTrashed()
 * @method static Builder|TransportInventory query()
 * @method static Builder|TransportInventory whereArrivalTimeConfirmed($value)
 * @method static Builder|TransportInventory whereArrivesAt($value)
 * @method static Builder|TransportInventory whereCreatedAt($value)
 * @method static Builder|TransportInventory whereDeletedAt($value)
 * @method static Builder|TransportInventory whereDepartsAt($value)
 * @method static Builder|TransportInventory whereDepartureTimeConfirmed($value)
 * @method static Builder|TransportInventory whereFitSelectable($value)
 * @method static Builder|TransportInventory whereId($value)
 * @method static Builder|TransportInventory whereNotes($value)
 * @method static Builder|TransportInventory wherePurchasePrice($value)
 * @method static Builder|TransportInventory whereSalesPrice($value)
 * @method static Builder|TransportInventory whereStock($value)
 * @method static Builder|TransportInventory whereTransportId($value)
 * @method static Builder|TransportInventory whereTravelClassId($value)
 * @method static Builder|TransportInventory whereTransportOccupancyId($value)
 * @method static Builder|TransportInventory whereUpdatedAt($value)
 * @method static QueryBuilder|TransportInventory withTrashed()
 * @method static QueryBuilder|TransportInventory withoutTrashed()
 * @mixin Eloquent
 */
class TransportInventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [];
    protected array $cascadeDeletes = ['tourComponents'];
    protected $casts = [
        'departs_at' => 'datetime',
        'arrives_at' => 'datetime',
        'sales_price' => 'double',
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
    ];

    private TransportInventoryRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'travel_class_id' => 'required|exists:travel_classes,id',
            'transport_occupancy_id' => 'required|exists:transport_occupancies,id',
            'departs_at' => 'date',
            'arrives_at' => 'date',
            'stock' => 'required|numeric|integer',
            'purchase_price' => 'nullable|numeric',
            'sales_price' => 'nullable|numeric',
        ];
    }

    public static function findByTour($tour_id): Collection|array
    {
        return TransportInventory::with(['tour' => function ($q) use ($tour_id) {
            $q->where('tour_id', $tour_id);
        }])->with('departureAddress', 'arrivalAddress')->get();
    }

    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    public function contractComponents(): MorphMany
    {
        return $this->morphMany(SupplierContractComponent::class, 'component');
    }

    public function tour(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'transport_inventory_tour')->withPivot('sales_price');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(OrderTransport::class, TransportInventoryTour::class, 'transport_inventory_id', 'transport_inventory_tour_id');
    }

    public function travelClass(): BelongsTo
    {
        return $this->belongsTo(TravelClass::class, 'travel_class_id');
    }

    public function getTransportForTourAttribute(): string
    {
        if (empty($this->transport)) {
            return 'not yet set';
        }

        $departs_at = $this->departs_at->format('d/m/Y H:i');
        $arrives_at = $this->arrives_at->format('d/m/Y H:i');

        return "{$this->transport->name}｜Departs from: {$this->transport->departureAddress->name} - Arrives at: {$this->transport->arrivalAddress->name}｜Departs: {$departs_at} - Arrives: {$arrives_at}";
        // build server edit: remove transport travelClass
        //return "{$this->transport->name}｜Departs from: {$departure_location->name} - Arrives at: {$arrival_location->name}｜Departs: {$departs_at} - Arrives: {$arrives_at}｜Travel Class: {$this->travelClass->name}";
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getContractedAttribute(): int
    {
        return $this->contractComponents()->sum('quantity');
    }

    public function getUsedOnTourCountAttribute(): int
    {
        return $this->tourComponents()->count();
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(TransportInventoryTour::class, 'transport_inventory_id');
    }

    public function quoteComponents(): HasMany
    {
        return $this->hasMany(QuoteTransport::class, 'transport_inventory_id');
    }

    public function __toString(): string
    {
        return "{$this->component} - {$this->travelClass} (" . f_datetime($this->departs_at) . " to " . f_datetime($this->arrives_at) . ")";
    }

    public function getRepositoryAttribute(): TransportInventoryRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new TransportInventoryRepository($this);
        return $this->internal_repository;
    }

    public function getLocalPurchasePriceAttribute(): float|null
    {
        return fx_convert($this->purchase_price, $this->component->currency);
    }

    public function transportOccupancy(): BelongsTo
    {
        return $this->belongsTo(TransportOccupancy::class, 'transport_occupancy_id');
    }

    public function hasSufficientOccupancy(int $passengerCount): bool
    {
        $maxOccupancy = $this->transportOccupancy?->maximum_occupancy;

        return is_null($maxOccupancy) || $maxOccupancy >= $passengerCount;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::creating(function (TransportInventory $transportInventory) {
            if (auth()->check() && !$transportInventory->created_by) {
                $transportInventory->created_by = auth()->id();
            }
        });
    }

}
