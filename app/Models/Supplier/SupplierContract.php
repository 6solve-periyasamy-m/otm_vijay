<?php

namespace App\Models\Supplier;

use Database\Factories\Supplier\SupplierContractFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier\SupplierContract
 *
 * @property int $id
 * @property string|null $purchase_order_number
 * @property int $currency_id
 * @property string $agreed_exchange
 * @property string $total_cost
 * @property string|null $price_per_item
 * @property int $confirmed
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 * @mixin Eloquent
 */
class SupplierContract extends Model
{
    use HasFactory;
}
