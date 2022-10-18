<?php

namespace App\Models\Quote\Component;

use App\Models\Flight\FlightInventory;
use App\Models\Quote\Quote;
use App\Repository\Model\Quote\Component\QuoteFlightRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Component\QuoteFlight
 *
 * @property int $id
 * @property int $quote_id
 * @property int $flight_inventory_id
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property string|null $flight_type
 * @property bool $price_shown
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Carbon $end
 * @property-read float $margin
 * @property-read float $purchase_price
 * @property-read QuoteFlightRepository $repository
 * @property-read Carbon $start
 * @property-read FlightInventory $inventory
 * @property-read Quote $quote
 * @method static Builder|QuoteFlight newModelQuery()
 * @method static Builder|QuoteFlight newQuery()
 * @method static QueryBuilder|QuoteFlight onlyTrashed()
 * @method static Builder|QuoteFlight query()
 * @method static Builder|QuoteFlight whereCreatedAt($value)
 * @method static Builder|QuoteFlight whereDeletedAt($value)
 * @method static Builder|QuoteFlight whereFlightInventoryId($value)
 * @method static Builder|QuoteFlight whereFlightType($value)
 * @method static Builder|QuoteFlight whereId($value)
 * @method static Builder|QuoteFlight whereQuoteId($value)
 * @method static Builder|QuoteFlight whereTourComponentType($value)
 * @method static Builder|QuoteFlight whereTourSalesPrice($value)
 * @method static Builder|QuoteFlight whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteFlight withTrashed()
 * @method static QueryBuilder|QuoteFlight withoutTrashed()
 * @mixin Eloquent
 */
class QuoteFlight extends Model
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
        return $this->belongsTo(FlightInventory::class, 'flight_inventory_id');
    }

    public function getRepositoryAttribute(): QuoteFlightRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteFlightRepository($this);
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
