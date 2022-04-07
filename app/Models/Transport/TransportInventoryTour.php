<?php

namespace App\Models\Transport;

use App\Models\Order\Component\OrderTransport;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\TransportComponentRepository;
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
 * App\Models\Transport\TransportInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $transport_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $available_stock
 * @property-read string $tour_name
 * @property-read TransportInventory $inventory
 * @property-read Collection|OrderTransport[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read TransportInventory $transportInventory
 * @property-read Collection|TransportInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|TransportInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @method static Builder|TransportInventoryTour newModelQuery()
 * @method static Builder|TransportInventoryTour newQuery()
 * @method static QueryBuilder|TransportInventoryTour onlyTrashed()
 * @method static Builder|TransportInventoryTour query()
 * @method static Builder|TransportInventoryTour whereCreatedAt($value)
 * @method static Builder|TransportInventoryTour whereDeletedAt($value)
 * @method static Builder|TransportInventoryTour whereId($value)
 * @method static Builder|TransportInventoryTour whereTourComponentType($value)
 * @method static Builder|TransportInventoryTour whereTourId($value)
 * @method static Builder|TransportInventoryTour whereTourSalesPrice($value)
 * @method static Builder|TransportInventoryTour whereTransportInventoryId($value)
 * @method static Builder|TransportInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|TransportInventoryTour withTrashed()
 * @method static QueryBuilder|TransportInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class TransportInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['tour_id', 'transport_inventory_id', 'tour_sales_price', 'tour_component_type'];
    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];

    public static function getValidationRules(): array
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'transport_inventory_id' => 'required|exists:transport_inventories,id'
        ];
    }

    public function transportInventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderTransport::class, 'transport_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(TransportInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): TransportInventoryTour
    {
        return TransportComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->transportInventory;
        $component = $inventory->transport;
        return $component->name . ' (' . $component->departureAddress->name . ' to ' .  $component->arrivalAddress->name . ')'.
            ' (' . $component->transportType->name . ') ' .
            ' (' . StringFormatter::formatDateTime($inventory->departs_at) . ' to ' . StringFormatter::formatDateTime($inventory->arrives_at) . ')' .
            ' (' . $inventory->travelClass->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function getUpgradeKeyMap(): array
    {
        $upgrades = $this->upgrades;
        $keys = [];
        if (empty($upgrades->all())) $upgrades = $this->parent()->upgrades;
        $keys[0] = 'Included - ' . StringFormatter::formatCurrency(0);
        foreach ($upgrades as $upgrade) {
            if ($upgrade->upgrade->available_stock <= 0) continue;
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
            if ($upgrade->upgrade->available_stock <= 0) continue;
            if ($this->tour_component_type == 'Included' || $upgrade->upgrade->tour_sales_price >= $this->tour_sales_price) {
                $keys[$upgrade->id] = $upgrade->description . ' - ' . StringFormatter::formatCurrency($upgrade->upgrade->tour_sales_price);
            }
        }
        return $keys;
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderTransport
    {
        return OrderTransport::create([
            'order_customer_id' => $orderCustomer->id,
            'transport_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }
}
