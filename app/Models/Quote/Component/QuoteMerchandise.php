<?php

namespace App\Models\Quote\Component;

use App\Models\Quote\Quote;
use App\Models\Tour\Merchandise;
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
 * @property int $merchandise_id
 * @property double $cost
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuoteMerchandiseRepository $repository
 * @property-read Merchandise|null $tourComponent
 * @property-read Quote|null $quote
 * @method static Builder|QuoteMerchandise newModelQuery()
 * @method static Builder|QuoteMerchandise newQuery()
 * @method static QueryBuilder|QuoteMerchandise onlyTrashed()
 * @method static Builder|QuoteMerchandise query()
 * @method static Builder|QuoteMerchandise whereCost($value)
 * @method static Builder|QuoteMerchandise whereCreatedAt($value)
 * @method static Builder|QuoteMerchandise whereDeletedAt($value)
 * @method static Builder|QuoteMerchandise whereId($value)
 * @method static Builder|QuoteMerchandise whereMerchandiseId($value)
 * @method static Builder|QuoteMerchandise whereQuoteId($value)
 * @method static Builder|QuoteMerchandise whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteMerchandise withTrashed()
 * @method static QueryBuilder|QuoteMerchandise withoutTrashed()
 * @mixin Eloquent
 */
class QuoteMerchandise extends Model
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
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function getRepositoryAttribute(): QuoteMerchandiseRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new QuoteMerchandiseRepository($this);
        return $this->internal_repository;
    }
}
