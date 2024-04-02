<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\Invoiced;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoiceAdjustment
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $description Why the adjustment was made
 * @property float $amount Amount adjusted
 * @property Carbon $date When the adjustment was made
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 * @method static Builder|InvoiceAdjustment newModelQuery()
 * @method static Builder|InvoiceAdjustment newQuery()
 * @method static Builder|InvoiceAdjustment query()
 * @method static Builder|InvoiceAdjustment whereAmount($value)
 * @method static Builder|InvoiceAdjustment whereCreatedAt($value)
 * @method static Builder|InvoiceAdjustment whereDate($value)
 * @method static Builder|InvoiceAdjustment whereDescription($value)
 * @method static Builder|InvoiceAdjustment whereId($value)
 * @method static Builder|InvoiceAdjustment whereInvoiceId($value)
 * @method static Builder|InvoiceAdjustment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceAdjustment extends Model
{
    use Invoiced;

    protected $casts = ['amount' => 'float', 'date' => 'datetime:Y-m-d H:i:s'];
    protected $guarded = [];
}
