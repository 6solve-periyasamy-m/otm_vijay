<?php

namespace App\Events\Parent;

use App\Models\OrderCustomerAdjustment;

abstract class OrderCustomerAdjustmentEvent extends OrderCustomerEvent
{
    /**
     * @var OrderCustomerAdjustment
     */
    public $orderAdjustment;

    /**
     * @param OrderCustomerAdjustment $orderAdjustment
     * @param bool $shouldInvoice
     */
    public function __construct($orderAdjustment, bool $shouldInvoice = true) {
        parent::__construct($orderAdjustment->orderCustomer, $shouldInvoice);
        $this->orderAdjustment = $orderAdjustment;
    }
}
