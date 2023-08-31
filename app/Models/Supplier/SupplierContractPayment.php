<?php

namespace App\Models\Supplier;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierContractPayment
 *
 * @property int $id
 * @property int $supplier_contract_id
 * @property string $amount
 * @property string|null $exchange_rate
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SupplierContract $contract
 * @method static Builder|SupplierContractPayment newModelQuery()
 * @method static Builder|SupplierContractPayment newQuery()
 * @method static Builder|SupplierContractPayment query()
 * @method static Builder|SupplierContractPayment whereAmount($value)
 * @method static Builder|SupplierContractPayment whereCreatedAt($value)
 * @method static Builder|SupplierContractPayment whereExchangeRate($value)
 * @method static Builder|SupplierContractPayment whereId($value)
 * @method static Builder|SupplierContractPayment whereNotes($value)
 * @method static Builder|SupplierContractPayment whereSupplierContractId($value)
 * @method static Builder|SupplierContractPayment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SupplierContractPayment extends Model
{
    public function contract(): BelongsTo
    {
        return $this->belongsTo(SupplierContract::class, 'supplier_contract_id');
    }
}
