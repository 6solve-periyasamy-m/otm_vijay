<?php

namespace App\Events\Parent;

use App\Models\OrderCustomer;

abstract class OrderCustomerEvent extends OrderEvent
{
    /**
     * @var OrderCustomer
     */
    public $orderCustomer;

    /**
     * @param OrderCustomer $orderCustomer
     */
    public function __construct(OrderCustomer $orderCustomer) {
        parent::__construct($orderCustomer->order);
        $this->orderCustomer = $orderCustomer;
    }
}
