<?php

namespace App\Models\Order\Invoice;

use App\Models\Order\Invoice\Traits\HasBillables;
use App\Models\Order\Invoice\Traits\Invoiced;
use Illuminate\Database\Eloquent\Model;

class InvoiceCustomer extends Model
{
    use Invoiced, HasBillables;
}
