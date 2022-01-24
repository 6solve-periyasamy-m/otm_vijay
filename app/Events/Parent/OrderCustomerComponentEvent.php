<?php

namespace App\Events\Parent;

use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;

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
