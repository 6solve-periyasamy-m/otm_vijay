<?php

namespace App\Models\Order\Invoice;

use App\Repository\Model\Order\InvoiceGenerator;
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
 * @property string $description Description of what is being billed
 * @property string $shared_key Shared key to be used if merging customer billing records for quantity
 * @property float $amount Cost of the billable item
 * @property boolean $is_base Is it an included component (part of the base package)
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
    protected $casts = ['amount' => 'float'];

    public function billed(): MorphTo
    {
        return $this->morphTo('billed');
    }

    public function isGroupedBase(): bool
    {
        return $this->shared_key === InvoiceGenerator::BASE_KEY;
    }
}
