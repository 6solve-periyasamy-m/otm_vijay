<?php

namespace App\Models\Tour;

use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Tour\MerchandiseRepository;
use Database\Factories\Tour\MerchandiseFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * App\Models\Tour\Merchandise
 *
 * @property int $id
 * @property string $name
 * @property string $tour_component_type
 * @property int $tour_id
 * @property string|null $image_url
 * @property int $stock
 * @property float $purchase_price
 * @property float $tour_sales_price
 * @property bool $is_bookable
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $available_stock
 * @property-read int $used_stock
 * @property-read Collection|OrderMerchandise[] $orderMerchandise
 * @property-read int|null $order_merchandise_count
 * @property-read Tour $tour
 * @property-read MerchandiseRepository $repository
 * @method static MerchandiseFactory factory(...$parameters)
 * @method static Builder|Merchandise newModelQuery()
 * @method static Builder|Merchandise newQuery()
 * @method static QueryBuilder|Merchandise onlyTrashed()
 * @method static Builder|Merchandise query()
 * @method static Builder|Merchandise whereCreatedAt($value)
 * @method static Builder|Merchandise whereDeletedAt($value)
 * @method static Builder|Merchandise whereId($value)
 * @method static Builder|Merchandise whereImageUrl($value)
 * @method static Builder|Merchandise whereName($value)
 * @method static Builder|Merchandise whereNotes($value)
 * @method static Builder|Merchandise wherePurchasePrice($value)
 * @method static Builder|Merchandise whereStock($value)
 * @method static Builder|Merchandise whereTourComponentType($value)
 * @method static Builder|Merchandise whereTourId($value)
 * @method static Builder|Merchandise whereTourSalesPrice($value)
 * @method static Builder|Merchandise whereUpdatedAt($value)
 * @method static QueryBuilder|Merchandise withTrashed()
 * @method static QueryBuilder|Merchandise withoutTrashed()
 * @mixin Eloquent
 */
class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'tour_component_type', 'stock', 'purchase_price', 'tour_sales_price', 'notes', 'image_url'];
    protected $casts = ['purchase_price' => 'double', 'tour_sales_price' => 'double', 'is_bookable' => 'boolean',];

    private MerchandiseRepository $internal_repository;

    public static function getValidationRules(): array
    {
        return [
            'name',
            'tour_component_type' => [
                'required',
                Rule::in([
                    'Included',
                    'Add-on',
                ])
            ],
            'stock' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderMerchandise(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'merchandise_id');
    }

    public function __toString(): string
    {
        return "{$this->name}";
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderMerchandise
    {
        return OrderMerchandise::create([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }

    public function getUsedStockAttribute(): int
    {
        return $this->repository->getUsedStock();
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->repository->getAvailableStock();
    }

    public function getRepositoryAttribute(): MerchandiseRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new MerchandiseRepository($this);
        return $this->internal_repository;
    }
}
