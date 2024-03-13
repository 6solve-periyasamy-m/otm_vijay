<?php

namespace App\Observers\Order;

use App\Models\Order\Order;
use App\Observers\UpdatesOrder;

class OrderObserver
{
    use UpdatesOrder;

    /**
     * Handle the Order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order)
    {
        $this->updateOrder($order);
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        $this->updateOrder($order);
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $this->updateOrder($order);
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        $this->updateOrder($order);
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        $this->updateOrder($order);
    }
}
