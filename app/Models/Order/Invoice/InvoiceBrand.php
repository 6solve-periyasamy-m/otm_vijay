<?php

namespace App\Models\Order\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvoiceBrand extends Model
{
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'invoice_brand_id');
    }
}
