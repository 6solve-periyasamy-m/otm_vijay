<?php

namespace App\Observers\Order;

use App\Models\Order\OrderCustomer;
use App\Observers\UpdatesOrder;

class OrderCustomerObserver
{
    use UpdatesOrder;
    
    /**
     * Handle the OrderCustomer "created" event.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function created(OrderCustomer $orderCustomer)
    {
        $this->updateOrder($orderCustomer->order);
    }

    /**
     * Handle the OrderCustomer "updated" event.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function updated(OrderCustomer $orderCustomer)
    {
        $this->updateOrder($orderCustomer->order);
    }

    /**
     * Handle the OrderCustomer "deleted" event.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function deleted(OrderCustomer $orderCustomer)
    {
        $this->updateOrder($orderCustomer->order);
    }

    /**
     * Handle the OrderCustomer "restored" event.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function restored(OrderCustomer $orderCustomer)
    {
        $this->updateOrder($orderCustomer->order);
    }

    /**
     * Handle the OrderCustomer "force deleted" event.
     *
     * @param OrderCustomer $orderCustomer
     * @return void
     */
    public function forceDeleted(OrderCustomer $orderCustomer)
    {
        $this->updateOrder($orderCustomer->order);
    }
}
