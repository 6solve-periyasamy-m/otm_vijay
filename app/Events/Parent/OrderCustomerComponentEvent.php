<?php

namespace App\Events\Parent;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;

abstract class OrderCustomerComponentEvent extends OrderCustomerEvent
{
    /**
     * @var OrderAccommodation|OrderActivity|OrderFlight|OrderTransport|OrderMerchandise
     */
    public $orderComponent;

    /**
     * @param OrderAccommodation|OrderActivity|OrderFlight|OrderTransport|OrderMerchandise $orderComponent
     * @param bool $shouldInvoice
     */
    public function __construct($orderComponent, bool $shouldInvoice = true) {
        parent::__construct($orderComponent->orderCustomer, $shouldInvoice);
        $this->orderComponent = $orderComponent;
    }
}
