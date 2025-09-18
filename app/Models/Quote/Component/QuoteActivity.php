<?php

namespace App\Models\Quote\Component;

use App\Models\Activity\ActivityInventory;
use App\Models\Quote\Quote;
use App\Repository\Model\Quote\Component\QuoteActivityRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Component\QuoteActivity
 *
 * @property int $id
 * @property int $quote_id
 * @property int $activity_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property bool $price_shown
 * @property int|null $quantity
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $document_order
 * @property-read int $order
 * @property-read Carbon $end
 * @property-read float $margin
 * @property-read float $purchase_price
 * @property-read QuoteActivityRepository $repository
 * @property-read Carbon $start
 * @property-read ActivityInventory $inventory
 * @property-read Quote $quote
 * @method static Builder|QuoteActivity newModelQuery()
 * @method static Builder|QuoteActivity newQuery()
 * @method static QueryBuilder|QuoteActivity onlyTrashed()
 * @method static Builder|QuoteActivity query()
 * @method static Builder|QuoteActivity whereActivityInventoryId($value)
 * @method static Builder|QuoteActivity whereCreatedAt($value)
 * @method static Builder|QuoteActivity whereDeletedAt($value)
 * @method static Builder|QuoteActivity whereId($value)
 * @method static Builder|QuoteActivity whereQuoteId($value)
 * @method static Builder|QuoteActivity whereTourComponentType($value)
 * @method static Builder|QuoteActivity whereTourSalesPrice($value)
 * @method static Builder|QuoteActivity whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteActivity withTrashed()
 * @method static QueryBuilder|QuoteActivity withoutTrashed()
 * @mixin Eloquent
 */
class QuoteActivity extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['tour_sales_price' => 'double'];

    private QuoteActivityRepository $internal_repository;

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(ActivityInventory::class, 'activity_inventory_id');
    }

    public function getRepositoryAttribute(): QuoteActivityRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteActivityRepository($this);
        return $this->internal_repository;
    }

    public function getPurchasePriceAttribute(): float
    {
        return $this->inventory->purchase_price;
    }

    public function getMarginAttribute(): float
    {
        return $this->purchase_price == 0 ? 100 : ($this->tour_sales_price / $this->purchase_price) * 100;
    }

    public function getStartAttribute(): Carbon|null
    {
        return $this->inventory->starts_at;
    }

    public function getEndAttribute(): Carbon|null
    {
        return $this->inventory->ends_at;
    }

    public function getOrderAttribute(): int
    {
        return $this->document_order ?? 0;
    }
}
