<?php

namespace App\Events\Parent;

use App\Events\Parent\Traits\ShouldInvoice;
use App\Exceptions\MailDisabledException;
use App\Mail\Storage\PaymentMail;
use App\Models\Order\Payment\Payment;

abstract class PaymentEvent extends OrderEvent
{
    use ShouldInvoice;
    /**
     * @var Payment
     */
    public $payment;

    /**
     * @param Payment $payment
     * @param bool $shouldInvoice
     */
    public function __construct(Payment $payment, bool $shouldInvoice = true) {
        parent::__construct($payment->order, $shouldInvoice);
        $this->payment = $payment;
    }
    public function sendMail(string $code, string $email): void
    {
        try {
            (new PaymentMail($code))->send($email, $this->payment);
        } catch (MailDisabledException) {}
    }
}
