<?php

namespace App\Observers\Order\Component;

use App\Models\Order\Component\OrderAccommodation;
use App\Observers\UpdatesOrder;

class OrderAccommodationObserver
{
    use UpdatesOrder;

    /**
     * Handle the OrderAccommodation "created" event.
     *
     * @param OrderAccommodation $orderAccommodation
     * @return void
     */
    public function created(OrderAccommodation $orderAccommodation)
    {
        foreach ($orderAccommodation->group->orderCustomers as $orderCustomer) {
            $this->updateOrder($orderCustomer);
        }
    }

    /**
     * Handle the OrderAccommodation "updated" event.
     *
     * @param OrderAccommodation $orderAccommodation
     * @return void
     */
    public function updated(OrderAccommodation $orderAccommodation)
    {
        foreach ($orderAccommodation->group->orderCustomers as $orderCustomer) {
            $this->updateOrder($orderCustomer);
        }
    }

    /**
     * Handle the OrderAccommodation "deleted" event.
     *
     * @param OrderAccommodation $orderAccommodation
     * @return void
     */
    public function deleted(OrderAccommodation $orderAccommodation)
    {
        foreach ($orderAccommodation->group->orderCustomers as $orderCustomer) {
            $this->updateOrder($orderCustomer);
        }
    }

    /**
     * Handle the OrderAccommodation "restored" event.
     *
     * @param OrderAccommodation $orderAccommodation
     * @return void
     */
    public function restored(OrderAccommodation $orderAccommodation)
    {
        foreach ($orderAccommodation->group->orderCustomers as $orderCustomer) {
            $this->updateOrder($orderCustomer);
        }
    }

    /**
     * Handle the OrderAccommodation "force deleted" event.
     *
     * @param OrderAccommodation $orderAccommodation
     * @return void
     */
    public function forceDeleted(OrderAccommodation $orderAccommodation)
    {
        foreach ($orderAccommodation->group->orderCustomers as $orderCustomer) {
            $this->updateOrder($orderCustomer);
        }
    }
}
