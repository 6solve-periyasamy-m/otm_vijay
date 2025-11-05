<?php

namespace App\Models\Customer;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * \App\Models\Transport\MerchandiseCategory
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CustomerMerchandise> $merchandises
 * @property-read int|null $inventories_count
 * @method static Builder|MerchandiseCategory newModelQuery()
 * @method static Builder|MerchandiseCategory newQuery()
 * @method static Builder|MerchandiseCategory query()
 * @method static Builder|MerchandiseCategory whereCreatedAt($value)
 * @method static Builder|MerchandiseCategory whereId($value)
 * @method static Builder|MerchandiseCategory whereName($value)
 * @method static Builder|MerchandiseCategory whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MerchandiseCategory extends SimpleModel
{
    protected $guarded = [];

    public function merchandises(): HasMany
    {
        return $this->hasMany(CustomerMerchandise::class, 'merchandise_category_id');
    }
}

