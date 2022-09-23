<?php

namespace App\Models\Merchandise;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Merchandise\MerchandiseSizeRepository;
use Database\Factories\Merchandise\MerchandiseSizeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Merchandise\MerchandiseSize
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|MerchandiseInventory[] $inventories
 * @property-read MerchandiseSizeRepository $repository
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
class MerchandiseSize extends SimpleModel
{
    use HasFactory, SoftDeletes, HasRepository;

    protected $guarded = [];

    public function inventories(): HasMany
    {
        return $this->hasMany(MerchandiseInventory::class, 'merchandise_size_id');
    }
}
