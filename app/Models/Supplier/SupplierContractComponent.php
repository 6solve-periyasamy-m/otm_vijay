<?php

namespace App\Models\Supplier;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Activity\ActivityInventory;
use App\Models\Flight\FlightInventory;
use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Transport\TransportInventory;
use App\Repository\Abstracts\InventoryRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierContractComponent
 *
 * @property int $id
 * @property int $supplier_contract_id
 * @property string $component_type
 * @property int $component_id
 * @property int $quantity
 * @property float $cost_per_unit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SupplierContract $contract
 * @property-read AccommodationInventory|ActivityInventory|FlightInventory|TransportInventory|MerchandiseInventory $componentRelation
 * @method static Builder|SupplierContractComponent newModelQuery()
 * @method static Builder|SupplierContractComponent newQuery()
 * @method static Builder|SupplierContractComponent query()
 * @method static Builder|SupplierContractComponent whereComponentId($value)
 * @method static Builder|SupplierContractComponent whereComponentType($value)
 * @method static Builder|SupplierContractComponent whereCostPerUnit($value)
 * @method static Builder|SupplierContractComponent whereCreatedAt($value)
 * @method static Builder|SupplierContractComponent whereId($value)
 * @method static Builder|SupplierContractComponent whereQuantity($value)
 * @method static Builder|SupplierContractComponent whereSupplierContractId($value)
 * @method static Builder|SupplierContractComponent whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SupplierContractComponent extends Model
{

    protected $casts = ['cost_per_unit' => 'float'];
    protected $guarded = [];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SupplierContract::class, 'supplier_contract_id');
    }

    public function componentRelation(): MorphTo
    {
        return $this->morphTo('component');
    }

    public function attach(InventoryRepository $repository): Model
    {
        return $this->componentRelation()->associate($repository->get());
    }

    public function component(): InventoryRepository
    {
        return $this->componentRelation->repository;
    }
}
