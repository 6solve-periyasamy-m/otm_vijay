<?php

namespace App\Models\Quote\Component;

use App\Models\Quote\Quote;
use App\Models\Transport\TransportInventory;
use App\Repository\Model\Quote\Component\QuoteTransportRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Component\QuoteTransport
 *
 * @property int $id
 * @property int $quote_id
 * @property int $transport_inventory_id
 * @property string $tour_component_type
 * @property float|null $tour_sales_price
 * @property string $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Carbon $end
 * @property-read float $margin
 * @property-read float $purchase_price
 * @property-read QuoteTransportRepository $repository
 * @property-read Carbon $start
 * @property-read TransportInventory $inventory
 * @property-read Quote $quote
 * @method static Builder|QuoteTransport newModelQuery()
 * @method static Builder|QuoteTransport newQuery()
 * @method static QueryBuilder|QuoteTransport onlyTrashed()
 * @method static Builder|QuoteTransport query()
 * @method static Builder|QuoteTransport whereCost($value)
 * @method static Builder|QuoteTransport whereCreatedAt($value)
 * @method static Builder|QuoteTransport whereDeletedAt($value)
 * @method static Builder|QuoteTransport whereId($value)
 * @method static Builder|QuoteTransport whereQuoteId($value)
 * @method static Builder|QuoteTransport whereTourComponentType($value)
 * @method static Builder|QuoteTransport whereTourSalesPrice($value)
 * @method static Builder|QuoteTransport whereTransportInventoryId($value)
 * @method static Builder|QuoteTransport whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteTransport withTrashed()
 * @method static QueryBuilder|QuoteTransport withoutTrashed()
 * @mixin Eloquent
 */
class QuoteTransport extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['tour_sales_price' => 'double'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(TransportInventory::class, 'transport_inventory_id');
    }

    public function getRepositoryAttribute(): QuoteTransportRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteTransportRepository($this);
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

    public function getStartAttribute(): Carbon
    {
        return $this->inventory->departs_at;
    }

    public function getEndAttribute(): Carbon
    {
        return $this->inventory->arrives_at;
    }
}
