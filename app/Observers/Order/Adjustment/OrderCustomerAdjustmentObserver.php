<?php

namespace App\Observers\Order\Adjustment;

use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Observers\UpdatesOrder;

class OrderCustomerAdjustmentObserver
{
    use UpdatesOrder;

    /**
     * Handle the OrderCustomerAdjustment "created" event.
     *
     * @param OrderCustomerAdjustment $orderCustomerAdjustment
     * @return void
     */
    public function created(OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $this->updateOrder($orderCustomerAdjustment->orderCustomer->order);
    }

    /**
     * Handle the OrderCustomerAdjustment "updated" event.
     *
     * @param OrderCustomerAdjustment $orderCustomerAdjustment
     * @return void
     */
    public function updated(OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $this->updateOrder($orderCustomerAdjustment->orderCustomer->order);
    }

    /**
     * Handle the OrderCustomerAdjustment "deleted" event.
     *
     * @param OrderCustomerAdjustment $orderCustomerAdjustment
     * @return void
     */
    public function deleted(OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $this->updateOrder($orderCustomerAdjustment->orderCustomer->order);
    }

    /**
     * Handle the OrderCustomerAdjustment "restored" event.
     *
     * @param OrderCustomerAdjustment $orderCustomerAdjustment
     * @return void
     */
    public function restored(OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $this->updateOrder($orderCustomerAdjustment->orderCustomer->order);
    }

    /**
     * Handle the OrderCustomerAdjustment "force deleted" event.
     *
     * @param OrderCustomerAdjustment $orderCustomerAdjustment
     * @return void
     */
    public function forceDeleted(OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $this->updateOrder($orderCustomerAdjustment->orderCustomer->order);
    }
}
