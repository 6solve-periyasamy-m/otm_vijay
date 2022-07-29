<?php

namespace App\Models\Quote;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\SentQuote
 *
 * @property int $id
 * @property int|null $quote_id
 * @property string $sent
 * @property string $recipient
 * @property int $travelling
 * @property int $paying
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote|null $quote
 * @method static Builder|SentQuote newModelQuery()
 * @method static Builder|SentQuote newQuery()
 * @method static Builder|SentQuote query()
 * @method static Builder|SentQuote whereCreatedAt($value)
 * @method static Builder|SentQuote whereData($value)
 * @method static Builder|SentQuote whereId($value)
 * @method static Builder|SentQuote wherePaying($value)
 * @method static Builder|SentQuote whereQuoteId($value)
 * @method static Builder|SentQuote whereRecipient($value)
 * @method static Builder|SentQuote whereSent($value)
 * @method static Builder|SentQuote whereTravelling($value)
 * @method static Builder|SentQuote whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SentQuote extends Model
{
    protected $casts = ['data' => 'array',];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
