<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\HasBillables;
use App\Models\Order\Invoice\Traits\Invoiced;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoiceGroup
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $name
 * @property float $total_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, InvoiceBillable> $billables
 * @property-read int|null $billables_count
 * @property-read Invoice $invoice
 * @method static Builder|InvoiceGroup newModelQuery()
 * @method static Builder|InvoiceGroup newQuery()
 * @method static Builder|InvoiceGroup query()
 * @method static Builder|InvoiceGroup whereCreatedAt($value)
 * @method static Builder|InvoiceGroup whereId($value)
 * @method static Builder|InvoiceGroup whereInvoiceId($value)
 * @method static Builder|InvoiceGroup whereName($value)
 * @method static Builder|InvoiceGroup whereTotalCost($value)
 * @method static Builder|InvoiceGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceGroup extends Model
{
    use Invoiced, HasBillables;

    protected $guarded = [];
    protected $casts = ['total_cost' => 'float',];
    protected $with = ['billables',];
}
