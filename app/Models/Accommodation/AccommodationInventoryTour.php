<?php

namespace App\Models\Accommodation;

use App\Models\Customer\Group;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Tour\Tour;
use App\Repository\AccommodationComponentRepository;
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
 * App\Models\Accommodation\AccommodationInventoryTour
 *
 * @property int $id
 * @property int $tour_id
 * @property int $accommodation_inventory_id
 * @property float|null $tour_sales_price
 * @property bool $is_template
 * @property string $tour_component_type
 * @property string $booking_policy
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AccommodationInventory $accommodationInventory
 * @property-read int $available_stock How much stock is still available to be sold
 * @property-read string $tour_name
 * @property-read int $used_tour_stock
 * @property-read AccommodationInventory $inventory
 * @property-read Collection|OrderAccommodation[] $orders
 * @property-read int|null $orders_count
 * @property-read Tour $tour
 * @property-read Collection|AccommodationInventoryTourUpgrade[] $upgradeParents
 * @property-read int|null $upgrade_parents_count
 * @property-read Collection|AccommodationInventoryTourUpgrade[] $upgrades
 * @property-read int|null $upgrades_count
 * @method static Builder|AccommodationInventoryTour newModelQuery()
 * @method static Builder|AccommodationInventoryTour newQuery()
 * @method static QueryBuilder|AccommodationInventoryTour onlyTrashed()
 * @method static Builder|AccommodationInventoryTour query()
 * @method static Builder|AccommodationInventoryTour whereAccommodationInventoryId($value)
 * @method static Builder|AccommodationInventoryTour whereBookingPolicy($value)
 * @method static Builder|AccommodationInventoryTour whereCreatedAt($value)
 * @method static Builder|AccommodationInventoryTour whereDeletedAt($value)
 * @method static Builder|AccommodationInventoryTour whereId($value)
 * @method static Builder|AccommodationInventoryTour whereIsTemplate($value)
 * @method static Builder|AccommodationInventoryTour whereTourComponentType($value)
 * @method static Builder|AccommodationInventoryTour whereTourId($value)
 * @method static Builder|AccommodationInventoryTour whereTourSalesPrice($value)
 * @method static Builder|AccommodationInventoryTour whereUpdatedAt($value)
 * @method static QueryBuilder|AccommodationInventoryTour withTrashed()
 * @method static QueryBuilder|AccommodationInventoryTour withoutTrashed()
 * @mixin Eloquent
 */
class AccommodationInventoryTour extends Model
{
    use HasFactory, CascadeSoftDeletes, SoftDeletes;

    protected $fillable = ['tour_id', 'accommodation_inventory_id', 'tour_component_type', 'tour_sales_price', 'is_template'];
    protected array $cascadeDeletes = ['orders', 'upgrades', 'upgradeParents'];
    protected $casts = [
        'tour_sales_price' => 'double',
        'is_template' => 'boolean'
    ];

    public static function getValidationRules(): array
    {
        return [
            'tour_component_type' => [
                'required',
                Rule::in(['Included', 'Upgrade', 'Add-on'])
            ],
            'tour_sales_price' => 'required|numeric',
            'accommodation_inventory_id' => 'required|exists:accommodation_inventories,id'
        ];
    }

    public function accommodationInventory(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderAccommodation::class, 'accommodation_inventory_tour_id');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'base_id');
    }

    // Only used for Cascading Soft Deletes
    public function upgradeParents(): HasMany
    {
        return $this->hasMany(AccommodationInventoryTourUpgrade::class, 'upgrade_id');
    }

    public function parent(): AccommodationInventoryTour
    {
        return AccommodationComponentRepository::getParentComponent($this);
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function __toString(): string
    {
        $inventory = $this->accommodationInventory;
        $component = $inventory->accommodation;
        return $component->name . ' (' . StringFormatter::formatDateTime($inventory->check_in) . ' to ' . StringFormatter::formatDateTime($inventory->check_out) . ') (' . $inventory->roomType->name . ', ' . $inventory->boardType->name . ')';
    }

    public function getTourNameAttribute(): string
    {
        return $this->tour?->name ?? 'Tour Deleted';
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->inventory->stock - $this->inventory->used_stock;
    }

    public function addToOrder(Group $group): OrderAccommodation
    {
        return OrderAccommodation::create([
            'group_id' => $group->id,
            'accommodation_inventory_tour_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
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

    public function getUsedTourStockAttribute(): int
    {
        $used = 0;
        foreach ($this->orders as $orderComponent) {
            if (!$orderComponent->isCancelled()) $used++;
        }
        return $used;
    }
}
