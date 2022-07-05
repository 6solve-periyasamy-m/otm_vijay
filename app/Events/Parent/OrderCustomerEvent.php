<?php

namespace App\Events\Parent;

use App\Models\Order\OrderCustomer;

abstract class OrderCustomerEvent extends OrderEvent
{
    /**
     * @var OrderCustomer
     */
    public $orderCustomer;

    /**
     * @param OrderCustomer $orderCustomer
     * @param bool $shouldInvoice
     */
    public function __construct(OrderCustomer $orderCustomer, bool $shouldInvoice = true) {
        parent::__construct($orderCustomer->order, $shouldInvoice);
        $this->orderCustomer = $orderCustomer;
    }
}
