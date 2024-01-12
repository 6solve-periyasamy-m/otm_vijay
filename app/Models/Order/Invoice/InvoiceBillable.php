<?php

namespace App\Models\Order\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceBillable extends Model
{
    public function billed(): MorphTo
    {
        return $this->morphTo('billed');
    }
}
