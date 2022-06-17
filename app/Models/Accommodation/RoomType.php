<?php

namespace App\Models\Accommodation;

use Database\Factories\Accommodation\RoomTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\RoomType
 *
 * @property int $id
 * @property string $name
 * @property int $maximum_occupancy The maximum number of people that can be in a room of this type.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static RoomTypeFactory factory(...$parameters)
 * @method static Builder|RoomType newModelQuery()
 * @method static Builder|RoomType newQuery()
 * @method static QueryBuilder|RoomType onlyTrashed()
 * @method static Builder|RoomType query()
 * @method static Builder|RoomType whereCreatedAt($value)
 * @method static Builder|RoomType whereDeletedAt($value)
 * @method static Builder|RoomType whereId($value)
 * @method static Builder|RoomType whereMaximumOccupancy($value)
 * @method static Builder|RoomType whereName($value)
 * @method static Builder|RoomType whereUpdatedAt($value)
 * @method static QueryBuilder|RoomType withTrashed()
 * @method static QueryBuilder|RoomType withoutTrashed()
 * @mixin Eloquent
 */
class RoomType extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'maximum_occupancy',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:room_types,name', 'maximum_occupancy' => 'required|integer|min:1'];
    }

    public static function findOrCreate(string $name, int $maximumOccupancy): RoomType
    {
        $type = self::where('name', '=', $name)->first();
        if (!isset($type)) {
            $type = self::create(['name' => $name, 'maximum_occupancy' => $maximumOccupancy,]);
        }
        return $type;
    }

    public function __toString(): string
    {
        return $this->name . ' (Occupancy ' . $this->maximum_occupancy . ')';
    }
}
