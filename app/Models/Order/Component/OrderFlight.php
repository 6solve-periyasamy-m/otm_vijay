<?php

namespace App\Models\Order\Component;

use App\Models\Flight\Airport;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Order\Component\OrderFlightRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Component\OrderFlight
 *
 * @property int $id
 * @property int|null $order_customer_id
 * @property int|null $flight_inventory_tour_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property float|null $estimated_purchase_price
 * @property-read float $purchase_price
 * @property-read Airport|null $arrivalAirport
 * @property-read Airport|null $departureAirport
 * @property-read FlightInventoryTour|null $flightInventoryTour
 * @property-read string $atol_string
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read string $details
 * @property-read Flight $flight
 * @property-read FlightInventory $flight_inventory
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read OrderCustomer|null $orderCustomer
 * @property-read FlightInventoryTour|null $tourComponent
 * @property-read OrderFlightRepository $repository The repository used for calculations and storage
 * @method static Builder|OrderFlight newModelQuery()
 * @method static Builder|OrderFlight newQuery()
 * @method static QueryBuilder|OrderFlight onlyTrashed()
 * @method static Builder|OrderFlight query()
 * @method static Builder|OrderFlight whereCost($value)
 * @method static Builder|OrderFlight whereCreatedAt($value)
 * @method static Builder|OrderFlight whereDeletedAt($value)
 * @method static Builder|OrderFlight whereFlightInventoryTourId($value)
 * @method static Builder|OrderFlight whereId($value)
 * @method static Builder|OrderFlight whereOrderCustomerId($value)
 * @method static Builder|OrderFlight whereUpdatedAt($value)
 * @method static QueryBuilder|OrderFlight withTrashed()
 * @method static QueryBuilder|OrderFlight withoutTrashed()
 * @mixin Eloquent
 */
class OrderFlight extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double',];

    private OrderFlightRepository $internal_repository;

    public static function findByOrderCustomer($orderCustomerId): Collection|array
    {
        return OrderFlight::where('order_customer_id', $orderCustomerId)->with('arrivalAirport')->with('departureAirport')->get();
    }

    public static function compare(OrderFlight $a, OrderFlight $b): int
    {
        $aStart = $a->tourComponent->inventory->departs_at;
        $bStart = $b->tourComponent->inventory->departs_at;
        if ($aStart->gt($bStart)) return 1;
        if ($aStart->lt($bStart)) return -1;
        return 0;
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'flight_inventory_tour_id');
    }

    public function flightInventoryTour(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'flight_inventory_tour_id');
    }

    public function getFlightInventoryAttribute(): FlightInventory
    {
        return $this->flightInventoryTour->flightInventory;
    }

    public function getFlightAttribute(): Flight
    {
        return $this->flightInventoryTour->flightInventory->flight;
    }

    public function departureAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'departure_airport_id', 'id');
    }

    public function arrivalAirport(): HasOneThrough
    {
        return $this->hasOneThrough(Airport::class, Flight::class, 'arrival_airport_id', 'id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent->inventory} - {$this->tourComponent->flight_type}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function getAtolStringAttribute(): string
    {
        return $this->tourComponent->atol_string;
    }

    public function getRepositoryAttribute(): OrderFlightRepository
    {
        if (!isset ($this->internal_repository)) $this->internal_repository = new OrderFlightRepository($this);
        return $this->internal_repository;
    }

    public function swap(FlightInventoryTour $swap)
    {
        $this->flight_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }

    public function getPurchasePriceAttribute(): float
    {
        $inventory = $this->tourComponent->inventory;
        return $this->estimated_purchase_price ?? $inventory->local_purchase_price;
    }
}
