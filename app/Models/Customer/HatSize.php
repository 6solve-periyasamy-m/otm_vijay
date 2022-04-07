<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\HatSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|HatSize newModelQuery()
 * @method static Builder|HatSize newQuery()
 * @method static QueryBuilder|HatSize onlyTrashed()
 * @method static Builder|HatSize query()
 * @method static Builder|HatSize whereCreatedAt($value)
 * @method static Builder|HatSize whereDeletedAt($value)
 * @method static Builder|HatSize whereId($value)
 * @method static Builder|HatSize whereName($value)
 * @method static Builder|HatSize whereUpdatedAt($value)
 * @method static QueryBuilder|HatSize withTrashed()
 * @method static QueryBuilder|HatSize withoutTrashed()
 * @mixin Eloquent
 */
class HatSize extends SimpleModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:hat_sizes,name',];
    }
}
