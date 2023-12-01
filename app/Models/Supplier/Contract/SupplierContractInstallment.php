<?php

namespace App\Models\Supplier\Contract;

use App\Models\Supplier\SupplierContract;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\Contract\SupplierContractInstallment
 *
 * @property int $id
 * @property int $supplier_contract_id
 * @property float $amount
 * @property Carbon $due
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SupplierContract $contract
 * @method static Builder|SupplierContractInstallment newModelQuery()
 * @method static Builder|SupplierContractInstallment newQuery()
 * @method static Builder|SupplierContractInstallment query()
 * @method static Builder|SupplierContractInstallment whereAmount($value)
 * @method static Builder|SupplierContractInstallment whereCreatedAt($value)
 * @method static Builder|SupplierContractInstallment whereDue($value)
 * @method static Builder|SupplierContractInstallment whereId($value)
 * @method static Builder|SupplierContractInstallment whereSupplierContractId($value)
 * @method static Builder|SupplierContractInstallment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SupplierContractInstallment extends Model
{
    protected $casts = ['amount' => 'float', 'due' => 'date',];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SupplierContract::class, 'supplier_contract_id');
    }
}
