<?php

namespace App\Models\Quote\Component;

use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Quote\Quote;
use App\Repository\Model\Quote\Component\QuoteMerchandiseRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Component\QuoteMerchandise
 *
 * @property int $id
 * @property int $quote_id
 * @property int $merchandise_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read float $margin
 * @property-read QuoteMerchandiseRepository $repository
 * @property-read MerchandiseInventory $inventory
 * @property-read Quote $quote
 * @method static Builder|QuoteMerchandise newModelQuery()
 * @method static Builder|QuoteMerchandise newQuery()
 * @method static QueryBuilder|QuoteMerchandise onlyTrashed()
 * @method static Builder|QuoteMerchandise query()
 * @method static Builder|QuoteMerchandise whereCreatedAt($value)
 * @method static Builder|QuoteMerchandise whereDeletedAt($value)
 * @method static Builder|QuoteMerchandise whereId($value)
 * @method static Builder|QuoteMerchandise whereMerchandiseInventoryId($value)
 * @method static Builder|QuoteMerchandise whereNotes($value)
 * @method static Builder|QuoteMerchandise whereQuoteId($value)
 * @method static Builder|QuoteMerchandise whereTourComponentType($value)
 * @method static Builder|QuoteMerchandise whereTourSalesPrice($value)
 * @method static Builder|QuoteMerchandise whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteMerchandise withTrashed()
 * @method static QueryBuilder|QuoteMerchandise withoutTrashed()
 * @mixin Eloquent
 */
class QuoteMerchandise extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['purchase_price' => 'double', 'tour_sales_price' => 'double',];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(MerchandiseInventory::class, 'merchandise_inventory_id');
    }

    public function getRepositoryAttribute(): QuoteMerchandiseRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteMerchandiseRepository($this);
        return $this->internal_repository;
    }

    public function getMarginAttribute(): float
    {
        return $this->purchase_price == 0 ? 100 : ($this->tour_sales_price / $this->purchase_price) * 100;
    }
}
