<?php

namespace App\Observers\Order\Payment;

use App\Models\Order\Payment\Payment;
use App\Observers\UpdatesOrder;

class PaymentObserver
{
    use UpdatesOrder;

    /**
     * Handle the Payment "created" event.
     *
     * @param Payment $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        $this->updateOrder($payment->order);
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param Payment $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        $this->updateOrder($payment->order);
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param Payment $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        $this->updateOrder($payment->order);
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @param Payment $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        $this->updateOrder($payment->order);
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @param Payment $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        $this->updateOrder($payment->order);
    }
}
