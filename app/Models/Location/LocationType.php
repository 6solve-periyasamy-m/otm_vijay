<?php

namespace App\Models\Location;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;


/**
 * App\Models\Location\LocationType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|LocationType newModelQuery()
 * @method static Builder|LocationType newQuery()
 * @method static QueryBuilder|LocationType onlyTrashed()
 * @method static Builder|LocationType query()
 * @method static Builder|LocationType whereCreatedAt($value)
 * @method static Builder|LocationType whereDeletedAt($value)
 * @method static Builder|LocationType whereId($value)
 * @method static Builder|LocationType whereName($value)
 * @method static Builder|LocationType whereUpdatedAt($value)
 * @method static QueryBuilder|LocationType withTrashed()
 * @method static QueryBuilder|LocationType withoutTrashed()
 * @mixin Eloquent
 */
class LocationType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('location_types', 'name')->ignore($id),
                ],
            ];
        }
        return ['name' => 'required|unique:location_types,name',];
    }
}
