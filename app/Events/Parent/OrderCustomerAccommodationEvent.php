<?php

namespace App\Events\Parent;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\OrderCustomer;

abstract class OrderCustomerAccommodationEvent extends OrderCustomerEvent
{
    /**
     * @var OrderAccommodation
     */
    public $orderComponent;

    /**
     * @param OrderAccommodation $orderComponent
     * @param bool $shouldInvoice
     */
    public function __construct(OrderAccommodation $orderComponent, OrderCustomer $orderCustomer, bool $shouldInvoice = true) {
        parent::__construct($orderCustomer, $shouldInvoice);
        $this->orderComponent = $orderComponent;
    }
}
