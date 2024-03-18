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
 * @property string $booking_reference
 * @property int $invoice_number
 * @property string $name
 * @property bool $cancelled
 * @property Carbon $generated
 * @property string|null $invoice_footer
 * @property string|null $order_notes
 * @property int $invoice_brand_id
 * @property float $total_cost
 * @property float $total_paid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, InvoiceAdjustment> $adjustments
 * @property-read int|null $adjustments_count
 * @property-read InvoiceBrand $brand
 * @property-read InvoiceRepository $repository
 * @property-read Collection<int, InvoiceCustomer> $customers
 * @property-read int|null $customers_count
 * @property-read Collection<int, InvoiceGroup> $groups
 * @property-read int|null $groups_count
 * @property-read Collection<int, InvoiceInstallment> $installments
 * @property-read int|null $installments_count
 * @property-read InvoiceCustomer|null $lead
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
    protected $casts = ['generated' => 'datetime:Y-m-d H:i:s', 'total_cost' => 'float', 'total_paid' => 'float', 'cancelled' => 'boolean'];

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
