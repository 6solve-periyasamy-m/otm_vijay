<?php

namespace App\Models\Accommodation;

use App\Models\Helper\SimpleModel;
use Database\Factories\Accommodation\BoardTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;


/**
 * App\Models\BoardType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static BoardTypeFactory factory(...$parameters)
 * @method static Builder|BoardType newModelQuery()
 * @method static Builder|BoardType newQuery()
 * @method static QueryBuilder|BoardType onlyTrashed()
 * @method static Builder|BoardType query()
 * @method static Builder|BoardType whereCreatedAt($value)
 * @method static Builder|BoardType whereDeletedAt($value)
 * @method static Builder|BoardType whereId($value)
 * @method static Builder|BoardType whereName($value)
 * @method static Builder|BoardType whereUpdatedAt($value)
 * @method static QueryBuilder|BoardType withTrashed()
 * @method static QueryBuilder|BoardType withoutTrashed()
 * @mixin Eloquent
 */
class BoardType extends SimpleModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:board_types,name',];
    }
}
