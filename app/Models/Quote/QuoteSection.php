<?php

namespace App\Models\Quote;

use App\Models\Helper\Model;
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
 * @property bool $hidden
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quote $quote
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

    protected $casts = ['hidden' => 'boolean'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function getAssetAttribute(): ?string
    {
        return isset($this->image_url) ? asset($this->image_url) : null;
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
