<?php

namespace App\Models\Order\Component;

use App\Models\Order\OrderCustomer;
use App\Models\Transport;
use App\Models\TransportInventory;
use App\Models\TransportInventoryTour;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Component\OrderTransport
 *
 * @property int $id
 * @property int|null $order_customer_id
 * @property int|null $transport_inventory_tour_id
 * @property float $cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read bool $cancelled
 * @property-read string $details
 * @property-read string $tour_component_type
 * @property-read float $tour_sales_price
 * @property-read OrderCustomer|null $orderCustomer
 * @property-read TransportInventoryTour|null $transportInventoryTour
 * @property-read TransportInventoryTour|null $tourComponent
 * @method static Builder|OrderTransport newModelQuery()
 * @method static Builder|OrderTransport newQuery()
 * @method static QueryBuilder|OrderTransport onlyTrashed()
 * @method static Builder|OrderTransport query()
 * @method static Builder|OrderTransport whereCost($value)
 * @method static Builder|OrderTransport whereCreatedAt($value)
 * @method static Builder|OrderTransport whereDeletedAt($value)
 * @method static Builder|OrderTransport whereId($value)
 * @method static Builder|OrderTransport whereOrderCustomerId($value)
 * @method static Builder|OrderTransport whereTransportInventoryTourId($value)
 * @method static Builder|OrderTransport whereUpdatedAt($value)
 * @method static QueryBuilder|OrderTransport withTrashed()
 * @method static QueryBuilder|OrderTransport withoutTrashed()
 * @mixin Eloquent
 */
class OrderTransport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'transport_inventory_tour_id','cost'];

    public static function findByOrderCustomer($orderCustomerId): Collection|array
    {
        return OrderTransport::where('order_customer_id', $orderCustomerId)->get();
    }

    public function orderCustomer(): BelongsTo
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    public function tourComponent(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }

    public function transportInventoryTour(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }

    public function transportInventory(): TransportInventory
    {
        return $this->transportInventoryTour->transportInventory;
    }

    public function transport(): Transport
    {
        return $this->transportInventoryTour->transportInventory->transport;
    }

    public function getCancelledAttribute(): bool
    {
        return $this->orderCustomer->order->cancelled;
    }

    public function getDetailsAttribute(): string
    {
        return "{$this->tourComponent->inventory}";
    }

    public function getTourComponentTypeAttribute(): string
    {
        return $this->tourComponent->tour_component_type;
    }

    public function getTourSalesPriceAttribute(): float
    {
        return $this->tourComponent->tour_sales_price;
    }

    public function swap(TransportInventoryTour $swap)
    {
        $this->transport_inventory_tour_id = $swap->id;
        $this->cost = $swap->tour_sales_price;
        $this->save();
    }
}
