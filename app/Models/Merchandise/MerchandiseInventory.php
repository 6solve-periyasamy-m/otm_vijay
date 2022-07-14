<?php

namespace App\Models\Merchandise;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Merchandise\MerchandiseInventory
 *
 * @property int $id
 * @property int $merchandise_id
 * @property int $variant_id
 * @property int $fit_selectable
 * @property int $stock
 * @property string $purchase_price
 * @property string $sales_price
 * @property string|null $notes
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MerchandiseInventory newModelQuery()
 * @method static Builder|MerchandiseInventory newQuery()
 * @method static Builder|MerchandiseInventory query()
 * @method static Builder|MerchandiseInventory whereCreatedAt($value)
 * @method static Builder|MerchandiseInventory whereDeletedAt($value)
 * @method static Builder|MerchandiseInventory whereFitSelectable($value)
 * @method static Builder|MerchandiseInventory whereId($value)
 * @method static Builder|MerchandiseInventory whereMerchandiseId($value)
 * @method static Builder|MerchandiseInventory whereNotes($value)
 * @method static Builder|MerchandiseInventory wherePurchasePrice($value)
 * @method static Builder|MerchandiseInventory whereSalesPrice($value)
 * @method static Builder|MerchandiseInventory whereStock($value)
 * @method static Builder|MerchandiseInventory whereUpdatedAt($value)
 * @method static Builder|MerchandiseInventory whereVariantId($value)
 * @mixin Eloquent
 */
class MerchandiseInventory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'fit_selectable' => 'boolean',
        'purchase_price' => 'double',
        'sales_price' => 'double',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function tourComponents(): HasMany
    {
        return $this->hasMany(MerchandiseInventoryTour::class, 'merchandise_inventory_id');
    }
}
