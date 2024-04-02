<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\Invoiced;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoicePayment
 *
 * @property int $id
 * @property int $invoice_id
 * @property string|null $payee Who made the payment
 * @property float $amount How much was paid
 * @property Carbon $date When the payment was made
 * @property string $method What method was used to pay
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 * @method static Builder|InvoicePayment newModelQuery()
 * @method static Builder|InvoicePayment newQuery()
 * @method static Builder|InvoicePayment query()
 * @method static Builder|InvoicePayment whereAmount($value)
 * @method static Builder|InvoicePayment whereCreatedAt($value)
 * @method static Builder|InvoicePayment whereDate($value)
 * @method static Builder|InvoicePayment whereId($value)
 * @method static Builder|InvoicePayment whereInvoiceId($value)
 * @method static Builder|InvoicePayment whereMethod($value)
 * @method static Builder|InvoicePayment wherePayee($value)
 * @method static Builder|InvoicePayment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoicePayment extends Model
{
    use Invoiced;

    protected $casts = ['amount' => 'float', 'date' => 'datetime:Y-m-d H:i:s'];
    protected $guarded = [];
}
