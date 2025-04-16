<?php

namespace App\Models\Activity;

use Database\Factories\Activity\SeatingFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Activity\Seating
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static SeatingFactory factory($count = null, $state = [])
 * @method static Builder|Seating newModelQuery()
 * @method static Builder|Seating newQuery()
 * @method static Builder|Seating query()
 * @method static Builder|Seating whereCreatedAt($value)
 * @method static Builder|Seating whereDescription($value)
 * @method static Builder|Seating whereId($value)
 * @method static Builder|Seating whereName($value)
 * @method static Builder|Seating whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Seating extends Model
{
    use HasFactory;

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'seating_id');
    }
}
