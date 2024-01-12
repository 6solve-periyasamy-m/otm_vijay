<?php

namespace App\Models\Order\Invoice;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\InvoiceBrand
 *
 * @property int $id
 * @property string $name
 * @property string $website
 * @property string $email
 * @property string $telephone
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $town
 * @property string $region
 * @property string $country
 * @property string $postcode
 * @property string $vat_code
 * @property string $logo
 * @property string|null $header_image
 * @property string|null $footer_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice|null $invoice
 * @method static Builder|InvoiceBrand newModelQuery()
 * @method static Builder|InvoiceBrand newQuery()
 * @method static Builder|InvoiceBrand query()
 * @method static Builder|InvoiceBrand whereAddressLine1($value)
 * @method static Builder|InvoiceBrand whereAddressLine2($value)
 * @method static Builder|InvoiceBrand whereCountry($value)
 * @method static Builder|InvoiceBrand whereCreatedAt($value)
 * @method static Builder|InvoiceBrand whereEmail($value)
 * @method static Builder|InvoiceBrand whereFooterImage($value)
 * @method static Builder|InvoiceBrand whereHeaderImage($value)
 * @method static Builder|InvoiceBrand whereId($value)
 * @method static Builder|InvoiceBrand whereLogo($value)
 * @method static Builder|InvoiceBrand whereName($value)
 * @method static Builder|InvoiceBrand wherePostcode($value)
 * @method static Builder|InvoiceBrand whereRegion($value)
 * @method static Builder|InvoiceBrand whereTelephone($value)
 * @method static Builder|InvoiceBrand whereTown($value)
 * @method static Builder|InvoiceBrand whereUpdatedAt($value)
 * @method static Builder|InvoiceBrand whereVatCode($value)
 * @method static Builder|InvoiceBrand whereWebsite($value)
 * @mixin Eloquent
 */
class InvoiceBrand extends Model
{
    protected $guarded = [];

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'invoice_brand_id');
    }
}
