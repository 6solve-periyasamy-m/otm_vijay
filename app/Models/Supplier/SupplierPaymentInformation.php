<?php

namespace App\Models\Supplier;

use App\Models\System\Bank;
use Database\Factories\Supplier\SupplierPaymentInformationFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierPaymentInformation
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $bank_id
 * @property string $account_number
 * @property string|null $sort_code
 * @property string|null $bic_swift_code
 * @property string|null $iban
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Bank $bank
 * @property-read Supplier $supplier
 * @method static SupplierPaymentInformationFactory factory($count = null, $state = [])
 * @method static Builder|SupplierPaymentInformation newModelQuery()
 * @method static Builder|SupplierPaymentInformation newQuery()
 * @method static Builder|SupplierPaymentInformation query()
 * @method static Builder|SupplierPaymentInformation whereAccountNumber($value)
 * @method static Builder|SupplierPaymentInformation whereBankId($value)
 * @method static Builder|SupplierPaymentInformation whereBicSwiftCode($value)
 * @method static Builder|SupplierPaymentInformation whereCreatedAt($value)
 * @method static Builder|SupplierPaymentInformation whereIban($value)
 * @method static Builder|SupplierPaymentInformation whereId($value)
 * @method static Builder|SupplierPaymentInformation whereSortCode($value)
 * @method static Builder|SupplierPaymentInformation whereSupplierId($value)
 * @method static Builder|SupplierPaymentInformation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SupplierPaymentInformation extends Model
{
    use HasFactory;

    protected $with = ['bank',];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
