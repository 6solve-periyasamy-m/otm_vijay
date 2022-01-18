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
     */
    public function __construct($orderComponent) {
        parent::__construct($orderComponent->orderCustomer);
        $this->orderComponent = $orderComponent;
    }
}
