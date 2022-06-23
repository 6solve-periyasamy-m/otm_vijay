<?php

namespace App\Models\Quote\Component;

use App\Models\Flight\FlightInventoryTour;
use App\Models\Quote\QuoteTraveller;
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
 * @property int $quote_traveller_id
 * @property int $flight_inventory_tour_id
 * @property double $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FlightInventoryTour|null $tourComponent
 * @property-read QuoteTraveller|null $traveller
 * @method static Builder|QuoteFlight newModelQuery()
 * @method static Builder|QuoteFlight newQuery()
 * @method static QueryBuilder|QuoteFlight onlyTrashed()
 * @method static Builder|QuoteFlight query()
 * @method static Builder|QuoteFlight whereCost($value)
 * @method static Builder|QuoteFlight whereCreatedAt($value)
 * @method static Builder|QuoteFlight whereDeletedAt($value)
 * @method static Builder|QuoteFlight whereFlightInventoryTourId($value)
 * @method static Builder|QuoteFlight whereId($value)
 * @method static Builder|QuoteFlight whereQuoteTravellerId($value)
 * @method static Builder|QuoteFlight whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteFlight withTrashed()
 * @method static QueryBuilder|QuoteFlight withoutTrashed()
 * @mixin Eloquent
 */
class QuoteFlight extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double'];

    public function traveller(): BelongsTo
    {
        return $this->belongsTo(QuoteTraveller::class);
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class);
    }
}
