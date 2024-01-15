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
 * App\Models\Order\Invoice\InvoiceCustomer
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $full_name
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $town
 * @property string|null $region
 * @property string|null $country
 * @property string|null $postcode
 * @property bool $lead
 * @property float $total_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, InvoiceBillable> $billables
 * @property-read int|null $billables_count
 * @property-read Invoice $invoice
 * @method static Builder|InvoiceCustomer newModelQuery()
 * @method static Builder|InvoiceCustomer newQuery()
 * @method static Builder|InvoiceCustomer query()
 * @method static Builder|InvoiceCustomer whereAddressLine1($value)
 * @method static Builder|InvoiceCustomer whereAddressLine2($value)
 * @method static Builder|InvoiceCustomer whereCountry($value)
 * @method static Builder|InvoiceCustomer whereCreatedAt($value)
 * @method static Builder|InvoiceCustomer whereFullName($value)
 * @method static Builder|InvoiceCustomer whereId($value)
 * @method static Builder|InvoiceCustomer whereInvoiceId($value)
 * @method static Builder|InvoiceCustomer wherePostcode($value)
 * @method static Builder|InvoiceCustomer whereRegion($value)
 * @method static Builder|InvoiceCustomer whereTotalCost($value)
 * @method static Builder|InvoiceCustomer whereTown($value)
 * @method static Builder|InvoiceCustomer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceCustomer extends Model
{
    use Invoiced, HasBillables;

    protected $guarded = [];
    protected $casts = ['total_cost' => 'float', 'lead' => 'boolean'];
}
