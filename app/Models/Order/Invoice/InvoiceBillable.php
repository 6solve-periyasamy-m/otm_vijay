<?php

namespace App\Models\Order\Invoice;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoiceBillable
 *
 * @property int $id
 * @property string $billed_type
 * @property int $billed_id
 * @property string $description
 * @property string $shared_key
 * @property string $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read InvoiceCustomer|InvoiceGroup $billed
 * @method static Builder|InvoiceBillable newModelQuery()
 * @method static Builder|InvoiceBillable newQuery()
 * @method static Builder|InvoiceBillable query()
 * @method static Builder|InvoiceBillable whereAmount($value)
 * @method static Builder|InvoiceBillable whereBilledId($value)
 * @method static Builder|InvoiceBillable whereBilledType($value)
 * @method static Builder|InvoiceBillable whereCreatedAt($value)
 * @method static Builder|InvoiceBillable whereDescription($value)
 * @method static Builder|InvoiceBillable whereId($value)
 * @method static Builder|InvoiceBillable whereSharedKey($value)
 * @method static Builder|InvoiceBillable whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceBillable extends Model
{
    protected $guarded = [];

    public function billed(): MorphTo
    {
        return $this->morphTo('billed');
    }
}
