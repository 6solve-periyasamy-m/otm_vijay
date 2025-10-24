<?php

namespace App\Models\Quote;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Quote\QuoteCache
 *
 * @property int $id
 * @property int $quote_id
 * @property float|null $total Total cost of order, in system currency
 * @property float|null $cost_to_company Total cost to company in system currency
 * @property float|null $tax_amount
 * @property float|null $profit
 * @property float|null $margin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @method static Builder|QuoteCache newModelQuery()
 * @method static Builder|QuoteCache newQuery()
 * @method static Builder|QuoteCache query()
 * @method static Builder|QuoteCache whereCostToCompany($value)
 * @method static Builder|QuoteCache whereCreatedAt($value)
 * @method static Builder|QuoteCache whereId($value)
 * @method static Builder|QuoteCache whereMargin($value)
 * @method static Builder|QuoteCache whereProfit($value)
 * @method static Builder|QuoteCache whereQuoteId($value)
 * @method static Builder|QuoteCache whereTaxAmount($value)
 * @method static Builder|QuoteCache whereTotal($value)
 * @method static Builder|QuoteCache whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuoteCache extends Model
{
    protected $guarded = [];
    protected $casts = [
        'total' => 'double',
        'cost_to_company' => 'double',
        'tax_amount' => 'double',
        'profit' => 'double',
        'margin' => 'double',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
