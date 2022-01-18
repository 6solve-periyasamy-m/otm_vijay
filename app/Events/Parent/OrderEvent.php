<?php

namespace App\Events\Parent;

use App\Models\Order;

abstract class OrderEvent
{
    public $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }
}
