<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\Invoiced;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoiceInstallment
 *
 * @property int $id
 * @property int $invoice_id
 * @property Carbon $due When the installment is due
 * @property string $description Description of the installment
 * @property float $amount The amount for the installment
 * @property bool $paid Is the invoice fully paid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 * @method static Builder|InvoiceInstallment newModelQuery()
 * @method static Builder|InvoiceInstallment newQuery()
 * @method static Builder|InvoiceInstallment query()
 * @method static Builder|InvoiceInstallment whereAmount($value)
 * @method static Builder|InvoiceInstallment whereCreatedAt($value)
 * @method static Builder|InvoiceInstallment whereDue($value)
 * @method static Builder|InvoiceInstallment whereId($value)
 * @method static Builder|InvoiceInstallment whereInvoiceId($value)
 * @method static Builder|InvoiceInstallment wherePaid($value)
 * @method static Builder|InvoiceInstallment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceInstallment extends Model
{
    use Invoiced;

    protected $casts = ['amount' => 'float', 'due' => 'datetime:Y-m-d H:i:s', 'paid' => 'boolean'];
    protected $guarded = [];
}
