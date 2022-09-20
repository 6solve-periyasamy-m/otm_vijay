<?php

namespace App\Models\Merchandise;

use App\Models\Helper\SimpleModel;
use Database\Factories\Merchandise\VariantFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\Variant
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static VariantFactory factory(...$parameters)
 * @method static Builder|Variant newModelQuery()
 * @method static Builder|Variant newQuery()
 * @method static QueryBuilder|Variant onlyTrashed()
 * @method static Builder|Variant query()
 * @method static Builder|Variant whereCreatedAt($value)
 * @method static Builder|Variant whereDeletedAt($value)
 * @method static Builder|Variant whereId($value)
 * @method static Builder|Variant whereName($value)
 * @method static Builder|Variant whereUpdatedAt($value)
 * @method static QueryBuilder|Variant withTrashed()
 * @method static QueryBuilder|Variant withoutTrashed()
 * @mixin Eloquent
 */
class Variant extends SimpleModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function inventories(): HasMany
    {
        return $this->hasMany(MerchandiseInventory::class, 'variant_id');
    }
}
