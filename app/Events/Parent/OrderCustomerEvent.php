<?php

namespace App\Events\Parent;

use App\Exceptions\MailDisabledException;
use App\Mail\Storage\OrderCustomerMail;
use App\Models\Order\OrderCustomer;

abstract class OrderCustomerEvent extends OrderEvent
{
    /**
     * @var OrderCustomer
     */
    public $orderCustomer;

    /**
     * @param OrderCustomer $orderCustomer
     * @param bool $shouldInvoice
     */
    public function __construct(OrderCustomer $orderCustomer, bool $shouldInvoice = true) {
        parent::__construct($orderCustomer->order, $shouldInvoice);
        $this->orderCustomer = $orderCustomer;
    }

    public function sendMail(string $code, string $email): void
    {
        try {
            (new OrderCustomerMail($code))->send($email, $this->orderCustomer);
        } catch (MailDisabledException) {}
    }
}
