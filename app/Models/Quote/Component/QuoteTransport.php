<?php

namespace App\Models\Quote\Component;

use App\Models\Quote\Quote;
use App\Models\Transport\TransportInventoryTour;
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
 * @property int $quote_traveller_id
 * @property int $transport_inventory_tour_id
 * @property double $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuoteTransportRepository $repository
 * @property-read TransportInventoryTour|null $tourComponent
 * @property-read Quote|null $quote
 * @method static Builder|QuoteTransport newModelQuery()
 * @method static Builder|QuoteTransport newQuery()
 * @method static QueryBuilder|QuoteTransport onlyTrashed()
 * @method static Builder|QuoteTransport query()
 * @method static Builder|QuoteTransport whereCost($value)
 * @method static Builder|QuoteTransport whereCreatedAt($value)
 * @method static Builder|QuoteTransport whereDeletedAt($value)
 * @method static Builder|QuoteTransport whereId($value)
 * @method static Builder|QuoteTransport whereQuoteId($value)
 * @method static Builder|QuoteTransport whereTransportInventoryTourId($value)
 * @method static Builder|QuoteTransport whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteTransport withTrashed()
 * @method static QueryBuilder|QuoteTransport withoutTrashed()
 * @mixin Eloquent
 */
class QuoteTransport extends Model
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
        return $this->belongsTo(TransportInventoryTour::class);
    }

    public function getRepositoryAttribute(): QuoteTransportRepository
    {
        if (!isset($this->interal_repository)) $this->internal_repository = new QuoteTransportRepository($this);
        return $this->interal_repository;
    }
}
