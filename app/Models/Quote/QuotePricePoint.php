<?php

namespace App\Models\Quote;

use Database\Factories\Quote\QuotePricePointFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuotePricePoint
 *
 * @property int $id
 * @property int $quote_id
 * @property int $quantity
 * @property float $price_per_person
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @method static QuotePricePointFactory factory(...$parameters)
 * @method static Builder|QuotePricePoint newModelQuery()
 * @method static Builder|QuotePricePoint newQuery()
 * @method static QueryBuilder|QuotePricePoint onlyTrashed()
 * @method static Builder|QuotePricePoint query()
 * @method static Builder|QuotePricePoint whereCreatedAt($value)
 * @method static Builder|QuotePricePoint whereDeletedAt($value)
 * @method static Builder|QuotePricePoint whereId($value)
 * @method static Builder|QuotePricePoint wherePricePerPerson($value)
 * @method static Builder|QuotePricePoint whereQuantity($value)
 * @method static Builder|QuotePricePoint whereQuoteId($value)
 * @method static Builder|QuotePricePoint whereUpdatedAt($value)
 * @method static QueryBuilder|QuotePricePoint withTrashed()
 * @method static QueryBuilder|QuotePricePoint withoutTrashed()
 * @mixin Eloquent
 */
class QuotePricePoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'quantity' => 'integer',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
