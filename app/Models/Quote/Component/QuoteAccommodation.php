<?php

namespace App\Models\Quote\Component;

use App\Models\Accommodation\AccommodationInventoryTour;
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
 * @property int $quote_traveller_id
 * @property int $accommodation_inventory_tour_id
 * @property double $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuoteAccommodationRepository $repository
 * @property-read AccommodationInventoryTour|null $tourComponent
 * @property-read Quote|null $quote
 * @method static Builder|QuoteAccommodation newModelQuery()
 * @method static Builder|QuoteAccommodation newQuery()
 * @method static QueryBuilder|QuoteAccommodation onlyTrashed()
 * @method static Builder|QuoteAccommodation query()
 * @method static Builder|QuoteAccommodation whereAccommodationInventoryTourId($value)
 * @method static Builder|QuoteAccommodation whereCost($value)
 * @method static Builder|QuoteAccommodation whereCreatedAt($value)
 * @method static Builder|QuoteAccommodation whereDeletedAt($value)
 * @method static Builder|QuoteAccommodation whereId($value)
 * @method static Builder|QuoteAccommodation whereQuoteId($value)
 * @method static Builder|QuoteAccommodation whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteAccommodation withTrashed()
 * @method static QueryBuilder|QuoteAccommodation withoutTrashed()
 * @mixin Eloquent
 */
class QuoteAccommodation extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class);
    }

    public function getRepositoryAttribute(): QuoteAccommodationRepository
    {
        if (!isset($this->interal_repository)) $this->internal_repository = new QuoteAccommodationRepository($this);
        return $this->interal_repository;
    }
}
