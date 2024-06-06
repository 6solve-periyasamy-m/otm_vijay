<?php

namespace App\Models\Merchandise;

use App\Models\Supplier\SupplierContractComponent;
use App\Repository\Model\Merchandise\MerchandiseInventoryRepository;
use Database\Factories\Merchandise\MerchandiseInventoryFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\MerchandiseInventory
 *
 * @property int $id
 * @property int $merchandise_id
 * @property int $variant_id
 * @property int|null $merchandise_size_id
 * @property string|null $image_url
 * @property bool $fit_selectable
 * @property int $stock
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $internal_notes
 * @property string|null $external_notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int $contracted Amount of contracted stock
 * @property-read float $local_purchase_price FX Converted Purchase Price
 * @property-read Merchandise $component
 * @property-read string $asset
 * @property-read int $available_stock
 * @property-read MerchandiseInventoryRepository $repository
 * @property-read int $used_stock
 * @property-read MerchandiseSize|null $size
 * @property-read Collection|MerchandiseInventoryTour[] $tourComponents
 * @property-read Collection|SupplierContractComponent[] $contractComponents
 * @property-read int|null $tour_components_count
 * @property-read Variant $variant
 * @method static MerchandiseInventoryFactory factory(...$parameters)
 * @method static Builder|MerchandiseInventory newModelQuery()
 * @method static Builder|MerchandiseInventory newQuery()
 * @method static QueryBuilder|MerchandiseInventory onlyTrashed()
 * @method static Builder|MerchandiseInventory query()
 * @method static Builder|MerchandiseInventory whereCreatedAt($value)
 * @method static Builder|MerchandiseInventory whereDeletedAt($value)
 * @method static Builder|MerchandiseInventory whereFitSelectable($value)
 * @method static Builder|MerchandiseInventory whereId($value)
 * @method static Builder|MerchandiseInventory whereImageUrl($value)
 * @method static Builder|MerchandiseInventory whereMerchandiseId($value)
 * @method static Builder|MerchandiseInventory whereMerchandiseSizeId($value)
 * @method static Builder|MerchandiseInventory whereNotes($value)
 * @method static Builder|MerchandiseInventory wherePurchasePrice($value)
 * @method static Builder|MerchandiseInventory whereSalesPrice($value)
 * @method static Builder|MerchandiseInventory whereStock($value)
 * @method static Builder|MerchandiseInventory whereUpdatedAt($value)
 * @method static Builder|MerchandiseInventory whereVariantId($value)
 * @method static QueryBuilder|MerchandiseInventory withTrashed()
 * @method static QueryBuilder|MerchandiseInventory withoutTrashed()
 * @mixin Eloquent
 */
class MerchandiseInventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    private MerchandiseInventoryRepository $internal_repository;

    protected $guarded = [];

    protected $with = ['variant', 'size'];

    protected $casts = [
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function contractComponents(): MorphMany
    {
        return $this->morphMany(SupplierContractComponent::class, 'component');
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(MerchandiseInventoryTour::class, 'merchandise_inventory_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(MerchandiseSize::class, 'merchandise_size_id');
    }

    public function getRepositoryAttribute(): MerchandiseInventoryRepository
    {
        $this->internal_repository = $this->internal_repository ?? new MerchandiseInventoryRepository($this);
        return $this->internal_repository;
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getContractedAttribute(): int
    {
        return $this->contractComponents()->sum('quantity');
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->repository->getAvailableStock();
    }

    public function getAssetAttribute(): string
    {
        return isset($this->image_url) ? asset($this->image_url) : $this->component->asset;
    }

    public function __toString(): string
    {
        return "{$this->component} ({$this->variant->name}) ({$this->size->name})";
    }

    public function getLocalPurchasePriceAttribute(): float
    {
        return fx_convert($this->purchase_price, $this->component->currency);
    }
}
