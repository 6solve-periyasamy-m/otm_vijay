<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    protected $guarded = [];
    protected $with = ['brand', 'customers', 'lead', 'groups', 'adjustments', 'payments', 'installments',];

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
}
