<?php

namespace App\Models\Accommodation;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Accommodation\RoomCategory
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AccommodationInventory> $inventories
 * @property-read int|null $inventories_count
 * @method static Builder|RoomCategory newModelQuery()
 * @method static Builder|RoomCategory newQuery()
 * @method static Builder|RoomCategory query()
 * @method static Builder|RoomCategory whereCreatedAt($value)
 * @method static Builder|RoomCategory whereId($value)
 * @method static Builder|RoomCategory whereName($value)
 * @method static Builder|RoomCategory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RoomCategory extends SimpleModel
{
    protected $guarded = [];

    public function inventories(): HasMany
    {
        return $this->hasMany(AccommodationInventory::class, 'room_category_id');
    }
}
