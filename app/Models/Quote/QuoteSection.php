<?php

namespace App\Models\Quote;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteSection
 *
 * @property int $id
 * @property int $quote_id
 * @property string $title
 * @property string|null $body
 * @property float $order
 * @property string|null $image_url
 * @property bool $hidden
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @method static Builder|QuoteSection newModelQuery()
 * @method static Builder|QuoteSection newQuery()
 * @method static Builder|QuoteSection query()
 * @method static Builder|QuoteSection whereBody($value)
 * @method static Builder|QuoteSection whereCreatedAt($value)
 * @method static Builder|QuoteSection whereHidden($value)
 * @method static Builder|QuoteSection whereId($value)
 * @method static Builder|QuoteSection whereImageUrl($value)
 * @method static Builder|QuoteSection whereOrder($value)
 * @method static Builder|QuoteSection whereTitle($value)
 * @method static Builder|QuoteSection whereUpdatedAt($value)
 * @mixin Eloquent
 */
class QuoteSection extends Model
{
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
