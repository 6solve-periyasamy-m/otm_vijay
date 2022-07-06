<?php

namespace App\Models\Quote\Component;

use App\Models\Activity\ActivityInventoryTour;
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
 * @property int $activity_inventory_tour_id
 * @property double $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuoteActivityRepository $repository
 * @property-read ActivityInventoryTour|null $tourComponent
 * @property-read Quote|null $quote
 * @method static Builder|QuoteActivity newModelQuery()
 * @method static Builder|QuoteActivity newQuery()
 * @method static QueryBuilder|QuoteActivity onlyTrashed()
 * @method static Builder|QuoteActivity query()
 * @method static Builder|QuoteActivity whereActivityInventoryTourId($value)
 * @method static Builder|QuoteActivity whereCost($value)
 * @method static Builder|QuoteActivity whereCreatedAt($value)
 * @method static Builder|QuoteActivity whereDeletedAt($value)
 * @method static Builder|QuoteActivity whereId($value)
 * @method static Builder|QuoteActivity whereQuoteId($value)
 * @method static Builder|QuoteActivity whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteActivity withTrashed()
 * @method static QueryBuilder|QuoteActivity withoutTrashed()
 * @mixin Eloquent
 */
class QuoteActivity extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = ['cost' => 'double'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'activity_inventory_tour_id');
    }

    public function getRepositoryAttribute(): QuoteActivityRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteActivityRepository($this);
        return $this->internal_repository;
    }
}
