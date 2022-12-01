<?php

namespace App\Models\Tour;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property-read Tour $tour
 * @method static Builder|TourCost newModelQuery()
 * @method static Builder|TourCost newQuery()
 * @method static Builder|TourCost query()
 * @method static Builder|TourCost whereAmount($value)
 * @method static Builder|TourCost whereCreatedAt($value)
 * @method static Builder|TourCost whereId($value)
 * @method static Builder|TourCost whereName($value)
 * @method static Builder|TourCost wherePerCustomer($value)
 * @method static Builder|TourCost whereTourId($value)
 * @method static Builder|TourCost whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TourCost extends Model
{
    protected $guarded = [];
    protected $casts = ['per_customer' => 'boolean', 'amount' => 'float'];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
