<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\HasBillables;
use App\Models\Order\Invoice\Traits\Invoiced;
use Illuminate\Database\Eloquent\Model;

class InvoiceGroup extends Model
{
    use Invoiced, HasBillables;
}
