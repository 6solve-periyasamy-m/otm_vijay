<?php

namespace App\Repository\Mailing;

use App\Mail\Storage\OrderCustomerMail;
use App\Mail\Storage\OrderMail;
use App\Mail\Storage\PaymentMail;
use App\Mail\Storage\QuoteMail;
use App\Mail\Storage\SettingsMail;
use App\Mail\Storage\TemplatedMail;

class MailRepository
{
    public static function getMail(string $mail): TemplatedMail|null
    {
        return array_key_exists($mail, self::getAvailableMail()) ? self::getAvailableMail()[$mail] : null;
    }

    /**
     * Get a list of available mail
     * @return array<string, TemplatedMail>
     */
    public static function getAvailableMail(): array
    {
        return [
            'booking-confirmation' => new OrderMail('booking-confirmation'),
            'payment-due' => new OrderMail('payment-due'),
            'payment-overdue' => new OrderMail('payment-overdue'),
            'final-payment-due' => new OrderMail('final-payment-due'),
            'final-payment-overdue' => new OrderMail('final-payment-overdue'),
            'payment-made' => new PaymentMail('payment-made'),
            'refund-given' => new PaymentMail('refund-given'),
            'additional-traveller-added' => new OrderCustomerMail('additional-traveller-added'),
            'additional-traveller-removed' => new OrderCustomerMail('additional-traveller-removed'),
            'order-changed' => new OrderMail('order-changed'),
            'order-cancelled' => new OrderMail('order-cancelled'),
            'quote' => new QuoteMail('quote'),
            'reservation-invoice-document' => new OrderMail('reservation-invoice-document'),
        ];
    }
}
