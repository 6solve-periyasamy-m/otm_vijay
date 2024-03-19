<?php

namespace App\Models\Order\Invoice\Traits;

use App\Models\Order\Invoice\Invoice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Invoiced
{
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
