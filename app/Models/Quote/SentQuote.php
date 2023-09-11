<?php

namespace App\Models\Quote;

use App\Models\Customer\Organization;
use App\Repository\Model\Quote\QuoteRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\SentQuote
 *
 * @property int $id
 * @property int|null $quote_id
 * @property Carbon $sent
 * @property string $recipient
 * @property int $travelling
 * @property int $paying
 * @property string $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote|null $quote
 * @property-read Quote $built
 * @property-read int $free Calculated number of free travellers
 * @property-read int $paid Calculated number of paid travellers
 * @property-read Organization|null $organization
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
    protected $guarded = [];

    protected $casts = ['sent' => 'datetime'];

    private Quote $builtData;

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function organization(): HasOneThrough
    {
        return $this->through('quote')->has('organization');
    }

    public function getBuiltAttribute(): Quote
    {
        if (!isset($builtData)) $builtData = QuoteRepository::deserialize(json_decode($this->data, true));
        return $builtData;
    }

    public function getFreeAttribute(): int
    {
        $bool = $this->built->leadTraveller->travelling && !$this->built->leadTraveller->paying;
        return $this->travelling + ($bool ? 1 : 0);
    }

    public function getPaidAttribute(): int
    {
        $bool = $this->built->leadTraveller->paying;
        return $this->paying + ($bool ? 1 : 0);
    }
}
