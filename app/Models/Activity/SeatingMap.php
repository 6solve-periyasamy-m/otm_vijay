<?php

namespace App\Models\Activity;

use App\Models\Helper\SimpleModel;
use Database\Factories\Activity\SeatingMapFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Activity\SeatingMap
 *
 * @property int $id
 * @property string $name
 * @property string $image_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $asset
 * @method static SeatingMapFactory factory($count = null, $state = [])
 * @method static Builder|SeatingMap newModelQuery()
 * @method static Builder|SeatingMap newQuery()
 * @method static Builder|SeatingMap query()
 * @method static Builder|SeatingMap whereCreatedAt($value)
 * @method static Builder|SeatingMap whereId($value)
 * @method static Builder|SeatingMap whereImageUrl($value)
 * @method static Builder|SeatingMap whereName($value)
 * @method static Builder|SeatingMap whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SeatingMap extends SimpleModel
{
    use HasFactory;

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function getAssetAttribute(): string
    {
        return asset($this->image_url);
    }
}
