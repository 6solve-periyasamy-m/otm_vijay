<?php

namespace App\Models\Flight;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\Airline
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Airline newModelQuery()
 * @method static Builder|Airline newQuery()
 * @method static QueryBuilder|Airline onlyTrashed()
 * @method static Builder|Airline query()
 * @method static Builder|Airline whereCreatedAt($value)
 * @method static Builder|Airline whereDeletedAt($value)
 * @method static Builder|Airline whereId($value)
 * @method static Builder|Airline whereName($value)
 * @method static Builder|Airline whereUpdatedAt($value)
 * @method static QueryBuilder|Airline withTrashed()
 * @method static QueryBuilder|Airline withoutTrashed()
 * @mixin Eloquent
 */
class Airline extends SimpleModel
{
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:airlines,name',];
    }
}
