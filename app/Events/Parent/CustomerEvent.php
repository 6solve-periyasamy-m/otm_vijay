<?php

namespace App\Events\Parent;

use App\Events\Parent\Traits\ShouldInvoice;
use App\Models\Customer;
use App\Models\Order;

abstract class CustomerEvent
{
    use ShouldInvoice;

    public $customer;

    public function __construct(Customer $customer, bool $shouldInvoice = true) {
        $this->customer = $customer;
        $this->shouldInvoice = $shouldInvoice;
    }
}
