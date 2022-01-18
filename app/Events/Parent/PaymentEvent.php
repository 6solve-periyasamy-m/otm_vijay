<?php

namespace App\Events\Parent;

use App\Models\Payment;

abstract class PaymentEvent extends OrderEvent
{
    /**
     * @var Payment
     */
    public $payment;

    /**
     * @param Payment $payment
     */
    public function __construct(Payment $payment) {
        parent::__construct($payment->order);
        $this->payment = $payment;
    }
}
