<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tour\TourCost
 *
 * @property int $id
 * @property int $tour_id
 * @property string $name
 * @property float $amount
 * @property bool $per_customer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|AdditionalCost newModelQuery()
 * @method static Builder|AdditionalCost newQuery()
 * @method static Builder|AdditionalCost query()
 * @method static Builder|AdditionalCost whereAmount($value)
 * @method static Builder|AdditionalCost whereCreatedAt($value)
 * @method static Builder|AdditionalCost whereId($value)
 * @method static Builder|AdditionalCost whereName($value)
 * @method static Builder|AdditionalCost wherePerCustomer($value)
 * @method static Builder|AdditionalCost whereTourId($value)
 * @method static Builder|AdditionalCost whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AdditionalCost extends Model
{
    protected $guarded = [];
    protected $casts = ['per_customer' => 'boolean', 'amount' => 'float'];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
