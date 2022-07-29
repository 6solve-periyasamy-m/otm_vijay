<?php

namespace App\Models\Quote\Component;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Quote\Quote;
use App\Repository\Model\Quote\Component\QuoteAccommodationRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\Component\QuoteAccommodation
 *
 * @property int $id
 * @property int $quote_id
 * @property int $accommodation_inventory_id
 * @property bool $is_template
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Carbon $end
 * @property-read float $margin
 * @property-read float $purchase_price
 * @property-read QuoteAccommodationRepository $repository
 * @property-read Carbon $start
 * @property-read AccommodationInventory $inventory
 * @property-read Quote $quote
 * @method static Builder|QuoteAccommodation newModelQuery()
 * @method static Builder|QuoteAccommodation newQuery()
 * @method static QueryBuilder|QuoteAccommodation onlyTrashed()
 * @method static Builder|QuoteAccommodation query()
 * @method static Builder|QuoteAccommodation whereAccommodationInventoryId($value)
 * @method static Builder|QuoteAccommodation whereCreatedAt($value)
 * @method static Builder|QuoteAccommodation whereDeletedAt($value)
 * @method static Builder|QuoteAccommodation whereId($value)
 * @method static Builder|QuoteAccommodation whereIsTemplate($value)
 * @method static Builder|QuoteAccommodation whereQuoteId($value)
 * @method static Builder|QuoteAccommodation whereTourComponentType($value)
 * @method static Builder|QuoteAccommodation whereTourSalesPrice($value)
 * @method static Builder|QuoteAccommodation whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteAccommodation withTrashed()
 * @method static QueryBuilder|QuoteAccommodation withoutTrashed()
 * @mixin Eloquent
 */
class QuoteAccommodation extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['tour_sales_price' => 'double', 'is_template' => 'boolean'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventory::class, 'accommodation_inventory_id');
    }

    public function getRepositoryAttribute(): QuoteAccommodationRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteAccommodationRepository($this);
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
        return $this->inventory->check_in;
    }

    public function getEndAttribute(): Carbon
    {
        return $this->inventory->check_out;
    }
}
