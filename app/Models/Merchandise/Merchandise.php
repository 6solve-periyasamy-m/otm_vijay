<?php

namespace App\Models\Merchandise;

use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Merchandise\MerchandiseRepository;
use Database\Factories\Merchandise\MerchandiseFactory;
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
 * App\Models\Merchandise\Merchandise
 *
 * @property int $id
 * @property int|null $merchandise_type_id
 * @property string $name
 * @property string|null $image_url
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $asset
 * @property-read MerchandiseRepository $repository
 * @property-read Collection|MerchandiseInventory[] $inventories
 * @property-read int|null $inventories_count
 * @property-read MerchandiseType|null $type
 * @method static MerchandiseFactory factory(...$parameters)
 * @method static Builder|Merchandise newModelQuery()
 * @method static Builder|Merchandise newQuery()
 * @method static QueryBuilder|Merchandise onlyTrashed()
 * @method static Builder|Merchandise query()
 * @method static Builder|Merchandise whereCreatedAt($value)
 * @method static Builder|Merchandise whereDeletedAt($value)
 * @method static Builder|Merchandise whereId($value)
 * @method static Builder|Merchandise whereImageUrl($value)
 * @method static Builder|Merchandise whereMerchandiseTypeId($value)
 * @method static Builder|Merchandise whereName($value)
 * @method static Builder|Merchandise whereNotes($value)
 * @method static Builder|Merchandise whereUpdatedAt($value)
 * @method static QueryBuilder|Merchandise withTrashed()
 * @method static QueryBuilder|Merchandise withoutTrashed()
 * @mixin Eloquent
 */
class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    private MerchandiseRepository $internal_repository;

    protected $guarded = [];

    protected $with = ['type',];

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

    public function inventories(): HasMany
    {
        return $this->hasMany(MerchandiseInventory::class, 'merchandise_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MerchandiseType::class, 'merchandise_type_id');
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

    public function getAssetAttribute(): string
    {
        return isset($this->image_url) ? asset($this->image_url) : "";
    }

    public function getRepositoryAttribute(): MerchandiseRepository
    {
        $this->internal_repository = $this->internal_repository ?? new MerchandiseRepository($this);
        return $this->internal_repository;
    }
}
