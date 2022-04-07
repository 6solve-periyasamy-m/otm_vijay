<?php

namespace App\Models\Activity;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\ActivityType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|ActivityType newModelQuery()
 * @method static Builder|ActivityType newQuery()
 * @method static QueryBuilder|ActivityType onlyTrashed()
 * @method static Builder|ActivityType query()
 * @method static Builder|ActivityType whereCreatedAt($value)
 * @method static Builder|ActivityType whereDeletedAt($value)
 * @method static Builder|ActivityType whereId($value)
 * @method static Builder|ActivityType whereName($value)
 * @method static Builder|ActivityType whereUpdatedAt($value)
 * @method static QueryBuilder|ActivityType withTrashed()
 * @method static QueryBuilder|ActivityType withoutTrashed()
 * @mixin Eloquent
 */
class ActivityType extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:activity_types,name',];
    }
}
