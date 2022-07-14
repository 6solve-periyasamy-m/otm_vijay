<?php

namespace App\Models\Merchandise;

use App\Models\Order\Component\OrderMerchandise;
use App\Repository\Model\Tour\MerchandiseInventoryTourRepository;
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
 * App\Models\Merchandise\MerchandiseInventoryTour
 *
 * @property int $id
 * @property int $merchandise_inventory_id
 * @property int $tour_id
 * @property string $tour_component_type
 * @property string $tour_sales_price
 * @property int $is_bookable
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MerchandiseInventoryTour newModelQuery()
 * @method static Builder|MerchandiseInventoryTour newQuery()
 * @method static Builder|MerchandiseInventoryTour query()
 * @method static Builder|MerchandiseInventoryTour whereCreatedAt($value)
 * @method static Builder|MerchandiseInventoryTour whereDeletedAt($value)
 * @method static Builder|MerchandiseInventoryTour whereId($value)
 * @method static Builder|MerchandiseInventoryTour whereIsBookable($value)
 * @method static Builder|MerchandiseInventoryTour whereMerchandiseInventoryId($value)
 * @method static Builder|MerchandiseInventoryTour whereTourComponentType($value)
 * @method static Builder|MerchandiseInventoryTour whereTourId($value)
 * @method static Builder|MerchandiseInventoryTour whereTourSalesPrice($value)
 * @method static Builder|MerchandiseInventoryTour whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MerchandiseInventoryTour extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(MerchandiseInventory::class, 'merchandise_inventory_id');
    }

    public function orderComponents(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'merchandise_inventory_tour_id');
    }

    public function getRepositoryAttribute(): MerchandiseInventoryTourRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new MerchandiseInventoryTourRepository($this);
        return $this->internal_repository;
    }
}
