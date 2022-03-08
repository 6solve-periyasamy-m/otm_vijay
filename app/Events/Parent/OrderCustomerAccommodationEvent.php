<?php

namespace App\Events\Parent;

use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;

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
