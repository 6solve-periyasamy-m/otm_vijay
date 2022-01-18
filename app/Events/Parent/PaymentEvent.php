<?php

namespace App\Events\Parent;

use App\Models\Payment;

abstract class PaymentEvent extends OrderEvent
{
    use ShouldInvoice;
    /**
     * @var Payment
     */
    public $payment;

    /**
     * @param Payment $payment
     */
    public function __construct(Payment $payment, bool $shouldInvoice = true) {
        parent::__construct($payment->order, $shouldInvoice);
        $this->payment = $payment;
    }
}
