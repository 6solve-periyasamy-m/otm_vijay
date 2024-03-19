<?php

namespace App\Models\Order\Invoice\Traits;

use App\Models\Order\Invoice\InvoiceBillable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasBillables
{
    public function billables(): MorphMany
    {
        return $this->morphMany(InvoiceBillable::class, 'billed');
    }
}
