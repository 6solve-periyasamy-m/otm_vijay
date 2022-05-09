<?php

namespace App\Models\Flight;

use App\Models\Order\Component\OrderFlight;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\FlightComponentRepository;
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
use Illuminate\Validation\Rule;
use StringFormatter;

/**
 * App\Models\Flight\FlightInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $flight_inventory_id
 * @property string $tour_component_type
 * @property float|null $tour_sales_price
 * @property string|null $flight_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read FlightInventory $flightInventory
 * @property-read string $atol_string
 * @property-read int $available_stock
 * @property-read string $flight_inventory_for_tour
 * @property-read string $tour_name
 * @property-read int $used_tour_stock
 * @property-read FlightInventory $inventory
 * @property-read Collection|OrderFlight[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read Collection|FlightInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|FlightInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @method static Builder|FlightInventoryTour newModelQuery()
 * @method static Builder|FlightInventoryTour newQuery()
 * @method static QueryBuilder|FlightInventoryTour onlyTrashed()
 * @method static Builder|FlightInventoryTour query()
 * @method static Builder|FlightInventoryTour whereCreatedAt($value)
 * @method static Builder|FlightInventoryTour whereDeletedAt($value)
 * @method static Builder|FlightInventoryTour whereFlightInventoryId($value)
 * @method static Builder|FlightInventoryTour whereFlightType($value)
 * @method static Builder|FlightInventoryTour whereId($value)
 * @method static Builder|FlightInventoryTour whereTourComponentType($value)
 * @method static Builder|FlightInventoryTour whereTourId($value)
 * @method static Builder|FlightInventoryTour whereTourSalesPrice($value)
 * @method static Builder|FlightInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|FlightInventoryTour withTrashed()
 * @method static QueryBuilder|FlightInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class FlightInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'flight_inventory_id', 'tour_component_type', 'flight_type', 'tour_sales_price',];
    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
    protected $casts = ['tour_sales_price' => 'double'];

    public static function getValidationRules(): array
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'flight_type' => [
                'required',
                Rule::in(['Inbound', 'Outbound']),
            ],
            'tour_sales_price' => 'required|numeric',
            'flight_inventory_id' => 'required|exists:flight_inventories,id'
        ];
    }

    public function flightInventory(): BelongsTo
    {
        return $this->belongsTo(FlightInventory::class, 'flight_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(FlightInventory::class, 'flight_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderFlight::class, 'flight_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(FlightInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(FlightInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): FlightInventoryTour
    {
        return FlightComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->flightInventory;
        $component = $inventory->flight;
        return $component->airline->name . ' (' . $inventory->flight_number . ') ' . $component->departureAirport->name . ' to ' . $component->arrivalAirport->name .
            ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAtolStringAttribute(): string
    {
        return "{$this->flight_type} - {$this->inventory->flight->departureAirport} | " .
            StringFormatter::formatDate($this->inventory->departs_at) .
            " | {$this->inventory->flight->arrivalAirport} | {$this->inventory->flight->airline}";
    }

    public function getFlightInventoryForTourAttribute(): string
    {
        return "{$this->flight_type} {$this->flight->flight_number}";
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function getUpgradeKeyMap(int $required = 1): array
    {
        $upgrades = $this->upgrades;
        $included = $this;
        $keys = [];
        if (empty($upgrades->all())) {
            $upgrades = $this->parent()->upgrades;
            $included =  $this->parent();
        }
        if ($included->available_stock > $required-1) {
            $keys[0] = 'Included - ' . StringFormatter::formatCurrency(0);
        }
        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->available_stock <= $required-1) continue;
            $keys[$upgrade->id] = $upgrade->description . ' - ' . StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price);
        }
        return $keys;
    }

    public function getCustomerUpgradeKeyMap(): array
    {
        $upgrades = $this->upgrades;
        $keys = [];
        if (empty($upgrades->all())) {
            $upgrades = $this->parent()->upgrades;
        }

        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->id == $this->id) continue;
            if (!$upgrade->upgrade->is_bookable) continue;
            if ($upgrade->upgrade->available_stock <= 0) continue;
            if ($this->tour_component_type == 'Included' || $upgrade->upgrade->tour_sales_price >= $this->tour_sales_price) {
                $keys[$upgrade->id] = $upgrade->description . ' - ' . StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price);
            }
        }
        return $keys;
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderFlight
    {
        return OrderFlight::create([
            'order_customer_id' => $orderCustomer->id,
            'flight_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }

    public function getUsedTourStockAttribute(): int
    {
        $used = 0;
        foreach ($this->orders as $orderComponent) {
            if (!$orderComponent->isCancelled()) $used++;
        }
        return $used;
    }
}
