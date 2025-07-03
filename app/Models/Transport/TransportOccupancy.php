<?php

namespace App\Models\Transport;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Transport\TransportOccupancy
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, TransportInventory> $inventories
 * @property-read int|null $inventories_count
 * @method static Builder|TransportOccupancy newModelQuery()
 * @method static Builder|TransportOccupancy newQuery()
 * @method static Builder|TransportOccupancy query()
 * @method static Builder|TransportOccupancy whereCreatedAt($value)
 * @method static Builder|TransportOccupancy whereId($value)
 * @method static Builder|TransportOccupancy whereName($value)
 * @method static Builder|TransportOccupancy whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TransportOccupancy extends SimpleModel
{
    protected $guarded = [];

    public function inventories(): HasMany
    {
        return $this->hasMany(TransportInventory::class, 'transport_occupancy_id');
    }
}
