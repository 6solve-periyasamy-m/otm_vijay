<?php

namespace App\Models\Quote;

use App\Models\Helper\SimpleModel;
use Database\Factories\Quote\QuoteSectionTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Quote\QuoteSectionType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, QuoteSection> $sections
 * @property-read int|null $sections_count
 * @method static QuoteSectionTypeFactory factory($count = null, $state = [])
 * @method static Builder|QuoteSectionType newModelQuery()
 * @method static Builder|QuoteSectionType newQuery()
 * @method static Builder|QuoteSectionType query()
 * @method static Builder|QuoteSectionType whereCreatedAt($value)
 * @method static Builder|QuoteSectionType whereId($value)
 * @method static Builder|QuoteSectionType whereName($value)
 * @method static Builder|QuoteSectionType whereUpdatedAt($value)
 * @mixin Eloquent
 */
class QuoteSectionType extends SimpleModel
{
    use HasFactory;

    public function sections(): HasMany
    {
        return $this->hasMany(QuoteSection::class, 'quote_section_type_id');
    }
}
