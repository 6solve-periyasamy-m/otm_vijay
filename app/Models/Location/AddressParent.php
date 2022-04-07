<?php

namespace App\Models\Location;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Location\AddressParent
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|AddressParent newModelQuery()
 * @method static Builder|AddressParent newQuery()
 * @method static QueryBuilder|AddressParent onlyTrashed()
 * @method static Builder|AddressParent query()
 * @method static Builder|AddressParent whereCreatedAt($value)
 * @method static Builder|AddressParent whereDeletedAt($value)
 * @method static Builder|AddressParent whereId($value)
 * @method static Builder|AddressParent whereName($value)
 * @method static Builder|AddressParent whereUpdatedAt($value)
 * @method static QueryBuilder|AddressParent withTrashed()
 * @method static QueryBuilder|AddressParent withoutTrashed()
 * @mixin Eloquent
 */
class AddressParent extends SimpleModel
{
    use HasFactory, SoftDeletes;

    const ID_MAP = [
        63 => 'Other',
        1 => 'Customer',
        2 => 'Accommodation',
        3 => 'Activity',
        4 => 'Airport',
        5 => 'Transport',
    ];

    public static function getParentId(string $key): int
    {
        return match (strtolower($key)) {
            'customer' => 1,
            'accommodation' => 2,
            'activity' => 3,
            'airport' => 4,
            'transport' => 5,
            default => 63,
        };
    }
}
