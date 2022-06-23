<?php

namespace App\Models\Quote;

use Database\Factories\Quote\QuoteInstallmentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteInstallment
 *
 * @property int $id
 * @property int $quote_id
 * @property Carbon $due_on
 * @property double $amount
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @method static QuoteInstallmentFactory factory(...$parameters)
 * @method static Builder|QuoteInstallment newModelQuery()
 * @method static Builder|QuoteInstallment newQuery()
 * @method static QueryBuilder|QuoteInstallment onlyTrashed()
 * @method static Builder|QuoteInstallment query()
 * @method static Builder|QuoteInstallment whereAmount($value)
 * @method static Builder|QuoteInstallment whereCreatedAt($value)
 * @method static Builder|QuoteInstallment whereDeletedAt($value)
 * @method static Builder|QuoteInstallment whereDueOn($value)
 * @method static Builder|QuoteInstallment whereId($value)
 * @method static Builder|QuoteInstallment whereQuoteId($value)
 * @method static Builder|QuoteInstallment whereUpdatedAt($value)
 * @method static QueryBuilder|QuoteInstallment withTrashed()
 * @method static QueryBuilder|QuoteInstallment withoutTrashed()
 * @mixin Eloquent
 */
class QuoteInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'due_on' => 'date',
        'amount' => 'double'
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
