<?php

namespace App\Models\System;

use App\Models\System\Brand;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\System\Faq
 *
 * @property int $id
 * @property string $question
 * @property string|null $answer
 * @property int|null $is_active
 * @property int|null $brand_id
 * @property string|null $vat_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Faq newModelQuery()
 * @method static Builder|Faq newQuery()
 * @method static Builder|Faq query()
 * @method static Builder|Faq whereCreatedAt($value)
 * @method static Builder|Faq whereId($value)
 * @method static Builder|Faq whereQuestion($value)
 * @method static Builder|Faq whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Faq extends Model
{
    protected $guarded = [];
    protected $fillable = ['question', 'answer', 'active'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
