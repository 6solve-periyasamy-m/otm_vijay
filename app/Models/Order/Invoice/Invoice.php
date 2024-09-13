<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Order;
use App\Repository\Model\Order\InvoiceRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Order\Invoice\Invoice
 *
 * @property int $id
 * @property int $order_id
 * @property string $booking_reference The booking reference for the order
 * @property int $invoice_number Current version number for the invoice
 * @property string $name The name of the tour that the order is placed for
 * @property string|null $event The name of the event
 * @property bool $cancelled Whether the order is cancelled
 * @property Carbon $generated Date that the invoice was generated
 * @property string|null $invoice_footer The footer section for the invoice
 * @property string|null $order_notes External notes for the order
 * @property string|null $tax_name Name of the tax bracket used on the order
 * @property float|null $tax_amount Amount of tax for the order
 * @property float|null $commission_amount The amount of the commission on the order
 * @property float|null $commission_percentage The percentage of the order that is commission
 * @property int $invoice_brand_id
 * @property float $total_cost Total cost of the order
 * @property float $total_paid Total amount paid to date
 * @property float $total_fees Total fees for the order
 * @property int $generator_version Version of the generator that was used
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, InvoiceAdjustment> $adjustments Any Order Adjustments
 * @property-read int|null $adjustments_count
 * @property-read InvoiceBrand $brand The brand details for the invoice
 * @property-read InvoiceRepository $repository
 * @property-read Collection<int, InvoiceCustomer> $customers All customers on the order
 * @property-read int|null $customers_count
 * @property-read Collection<int, InvoiceGroup> $groups Groups on the order with extra
 * @property-read int|null $groups_count
 * @property-read Collection<int, InvoiceInstallment> $installments
 * @property-read int|null $installments_count
 * @property-read InvoiceCustomer|null $lead The lead booker
 * @property-read Order $order
 * @property-read Collection<int, InvoicePayment> $payments
 * @property-read int|null $payments_count
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereBookingReference($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereGenerated($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereInvoiceBrandId($value)
 * @method static Builder|Invoice whereInvoiceFooter($value)
 * @method static Builder|Invoice whereInvoiceNumber($value)
 * @method static Builder|Invoice whereOrderId($value)
 * @method static Builder|Invoice whereOrderNotes($value)
 * @method static Builder|Invoice whereTotalCost($value)
 * @method static Builder|Invoice whereTotalPaid($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Invoice extends Model
{
    protected $guarded = [];
    protected $with = ['brand', 'customers', 'lead', 'groups', 'adjustments', 'payments', 'installments',];
    protected $casts = [
        'generated' => 'datetime:Y-m-d H:i:s',
        'total_cost' => 'float',
        'total_paid' => 'float',
        'cancelled' => 'boolean',
        'commission_amount' => 'float',
        'commission_percentage' => 'float',
    ];

    private InvoiceRepository $internal_repository;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(InvoiceBrand::class, 'invoice_brand_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(InvoiceCustomer::class, 'invoice_id');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(InvoiceCustomer::class, 'invoice_id')->where('lead', '=', true);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(InvoiceGroup::class, 'invoice_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(InvoiceInstallment::class, 'invoice_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(InvoiceAdjustment::class, 'invoice_id');
    }

    public function getRepositoryAttribute(): InvoiceRepository
    {
        $this->internal_repository = $this->internal_repository ?? new InvoiceRepository($this);
        return $this->internal_repository;
    }
}
