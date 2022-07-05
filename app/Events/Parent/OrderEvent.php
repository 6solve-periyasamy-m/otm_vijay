<?php

namespace App\Events\Parent;

use App\Events\Parent\Traits\ShouldInvoice;
use App\Models\Order\Order;

abstract class OrderEvent
{
    use ShouldInvoice;

    public $order;

    public function __construct(Order $order, bool $shouldInvoice = true) {
        $this->order = $order;
        $this->shouldInvoice = $shouldInvoice;
    }
}
