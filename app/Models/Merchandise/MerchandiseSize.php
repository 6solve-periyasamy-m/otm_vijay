<?php

namespace App\Models\Merchandise;

use Database\Factories\Merchandise\MerchandiseSizeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\MerchandiseSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static MerchandiseSizeFactory factory(...$parameters)
 * @method static Builder|MerchandiseSize newModelQuery()
 * @method static Builder|MerchandiseSize newQuery()
 * @method static QueryBuilder|MerchandiseSize onlyTrashed()
 * @method static Builder|MerchandiseSize query()
 * @method static Builder|MerchandiseSize whereCreatedAt($value)
 * @method static Builder|MerchandiseSize whereDeletedAt($value)
 * @method static Builder|MerchandiseSize whereId($value)
 * @method static Builder|MerchandiseSize whereName($value)
 * @method static Builder|MerchandiseSize whereUpdatedAt($value)
 * @method static QueryBuilder|MerchandiseSize withTrashed()
 * @method static QueryBuilder|MerchandiseSize withoutTrashed()
 * @mixin Eloquent
 */
class MerchandiseSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
