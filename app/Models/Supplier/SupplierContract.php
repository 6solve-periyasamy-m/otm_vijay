<?php

namespace App\Models\Supplier;

use Database\Factories\Supplier\SupplierContractFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierContract
 *
 * @property int $id
 * @property int $supplier_id
 * @property string|null $purchase_order_number
 * @property int $currency_id
 * @property float $agreed_exchange
 * @property float $total_cost
 * @property float|null $price_per_item
 * @property bool $confirmed
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Supplier $supplier
 * @property-read Collection<int, SupplierContractComponent> $components
 * @property-read int|null $components_count
 * @method static SupplierContractFactory factory($count = null, $state = [])
 * @method static Builder|SupplierContract newModelQuery()
 * @method static Builder|SupplierContract newQuery()
 * @method static Builder|SupplierContract query()
 * @method static Builder|SupplierContract whereAgreedExchange($value)
 * @method static Builder|SupplierContract whereConfirmed($value)
 * @method static Builder|SupplierContract whereCreatedAt($value)
 * @method static Builder|SupplierContract whereCurrencyId($value)
 * @method static Builder|SupplierContract whereId($value)
 * @method static Builder|SupplierContract whereNotes($value)
 * @method static Builder|SupplierContract wherePricePerItem($value)
 * @method static Builder|SupplierContract wherePurchaseOrderNumber($value)
 * @method static Builder|SupplierContract whereTotalCost($value)
 * @method static Builder|SupplierContract whereUpdatedAt($value)
 * @method static Builder|SupplierContract whereSupplierId($value)
 * @mixin Eloquent
 */
class SupplierContract extends Model
{
    use HasFactory;

    protected $casts = ['agreed_exchange' => 'float', 'total_cost' => 'float', 'price_per_item' => 'float', 'confirmed' => 'boolean'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(SupplierContractComponent::class, 'supplier_contract_id');
    }
}
