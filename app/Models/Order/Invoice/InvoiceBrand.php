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
 * @property string $name Name of the brand
 * @property string $website The website for the brand
 * @property string $email Contact email for the brand
 * @property string $telephone Contact number for the brand
 * @property string $address_line_1 The first line of the brands billing address
 * @property string $address_line_2 The second line of the brands billing address
 * @property string $town The town of the brands billing address
 * @property string $region The region of the brands billing address
 * @property string $country The country of the brands billing address
 * @property string $postcode The postcode of the brands billing address
 * @property string $vat_code The VAT Code of the Brand
 * @property string $logo The asset reference for the logo. Access with asset().
 * @property string|null $header_image The asset reference for the header image. Access with asset().
 * @property string|null $footer_image The asset reference for the footer image. Access with asset().
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
