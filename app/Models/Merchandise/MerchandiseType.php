<?php

namespace App\Models\Merchandise;

use App\Models\Helper\SimpleModel;
use Database\Factories\Merchandise\MerchandiseTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\MerchandiseType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static MerchandiseTypeFactory factory(...$parameters)
 * @method static Builder|MerchandiseType newModelQuery()
 * @method static Builder|MerchandiseType newQuery()
 * @method static QueryBuilder|MerchandiseType onlyTrashed()
 * @method static Builder|MerchandiseType query()
 * @method static Builder|MerchandiseType whereCreatedAt($value)
 * @method static Builder|MerchandiseType whereDeletedAt($value)
 * @method static Builder|MerchandiseType whereId($value)
 * @method static Builder|MerchandiseType whereName($value)
 * @method static Builder|MerchandiseType whereUpdatedAt($value)
 * @method static QueryBuilder|MerchandiseType withTrashed()
 * @method static QueryBuilder|MerchandiseType withoutTrashed()
 * @mixin Eloquent
 */
class MerchandiseType extends SimpleModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
