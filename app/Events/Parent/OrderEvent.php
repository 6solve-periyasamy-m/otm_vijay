<?php

namespace App\Events\Parent;

use App\Events\Parent\Traits\ShouldInvoice;
use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Models\Order\Order;

abstract class OrderEvent
{
    use ShouldInvoice;

    public $order;

    public function __construct(Order $order, bool $shouldInvoice = true) {
        $this->order = $order;
        $this->shouldInvoice = $shouldInvoice;
    }
    public function sendMail(string $code, string $email): void
    {
        try {
            $this->order->repository->mailer()->sendMail($code, $email);
        } catch (MailDisabledException|MailFailedException) {}
    }
}
