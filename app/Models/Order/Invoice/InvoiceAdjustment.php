<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\Invoiced;
use Illuminate\Database\Eloquent\Model;

class InvoiceAdjustment extends Model
{
    use Invoiced;
}
