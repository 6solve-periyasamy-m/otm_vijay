<?php

namespace App\Models\Quote;

use App\Models\Helper\Model;
use App\Models\Location\Currency;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int|null $quote_section_type_id
 * @property int|null $currency_id
 * @property Carbon|null $sort_date
 * @property bool $hidden
 * @property int|null $quantity
 * @property float|null $purchase_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
 * @property-read QuoteSectionType|null $type
 * @property-read float|null $local_purchase_price Purchase price converted into system currency
 * @property-read Currency|null $currency
 * @property-read string|null $asset
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
    protected $guarded = [];

    protected $casts = ['hidden' => 'boolean', 'purchase_price' => 'float', 'sort_date' => 'datetime:Y-m-d H:i'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(QuoteSectionType::class, 'quote_section_type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getAssetAttribute(): ?string
    {
        return isset($this->image_url) ? asset($this->image_url) : null;
    }

    public function getLocalPurchasePriceAttribute(): ?float
    {
        return fx_convert($this->purchase_price, $this->currency);
    }

    public function serialize(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'order' => $this->order,
            'hidden' => $this->getAttribute('hidden'),
            'image_url' => $this->image_url
        ];
    }
}
