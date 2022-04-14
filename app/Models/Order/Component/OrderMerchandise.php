<?php

namespace App\Models\Order\Component;

use App\Models\Order\OrderCustomer;
use App\Models\Tour\Merchandise;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Component\OrderMerchandise
 *
 * @property int $id
 * @property int $order_customer_id
 * @property int $merchandise_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read bool $cancelled Is the order cancelled?
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read Merchandise $merchandise
 * @property-read OrderCustomer $orderCustomer
 * @property-read Merchandise $tourComponent
 * @method static Builder|OrderMerchandise newModelQuery()
 * @method static Builder|OrderMerchandise newQuery()
 * @method static QueryBuilder|OrderMerchandise onlyTrashed()
 * @method static Builder|OrderMerchandise query()
 * @method static Builder|OrderMerchandise whereCost($value)
 * @method static Builder|OrderMerchandise whereCreatedAt($value)
 * @method static Builder|OrderMerchandise whereDeletedAt($value)
 * @method static Builder|OrderMerchandise whereId($value)
 * @method static Builder|OrderMerchandise whereMerchandiseId($value)
 * @method static Builder|OrderMerchandise whereOrderCustomerId($value)
 * @method static Builder|OrderMerchandise whereUpdatedAt($value)
 * @method static QueryBuilder|OrderMerchandise withTrashed()
 * @method static QueryBuilder|OrderMerchandise withoutTrashed()
 * @mixin Eloquent
 */
class OrderMerchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['merchandise_id', 'order_customer_id', 'cost'];
    protected $casts = ['cost' => 'double',];

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function merchandise(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price;
    }
}
