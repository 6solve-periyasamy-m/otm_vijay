<?php

namespace App\Models\Merchandise;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Merchandise\MerchandiseTypeRepository;
use Database\Factories\Merchandise\MerchandiseTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Merchandise\MerchandiseType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Merchandise[] $merchandise
 * @property-read MerchandiseTypeRepository $repository
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
    use HasFactory, SoftDeletes, HasRepository;

    protected $guarded = [];

    public function merchandise(): HasMany
    {
        return $this->hasMany(Merchandise::class, 'merchandise_type_id');
    }
}
