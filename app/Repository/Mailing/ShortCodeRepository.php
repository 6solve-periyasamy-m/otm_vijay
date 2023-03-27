<?php

namespace App\Repository\Mailing;

use App\Mail\Storage\OrderCustomerMail;
use App\Mail\Storage\OrderMail;
use App\Mail\Storage\PaymentMail;
use App\Mail\Storage\SettingsMail;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;

class ShortCodeRepository
{

    public static function getFromString(string $key): ?array
    {
        return match ($key) {
            'order' => self::getOrderShortCodes(),
            'payment' => self::getPaymentShortCodes(),
            'settings' => self::getSettingShortCodes(),
            'order-customer' => self::getOrderCustomerShortCodes(),
            default => null,
        };
    }

    public static function getOrderShortCodes(Order $order = null): array
    {
        return (new OrderMail())->getShortcodes($order);
    }

    public static function getSettingShortCodes(): array
    {
        return (new SettingsMail())->getShortcodes();
    }

    public static function getPaymentShortCodes(Payment $payment = null): array
    {
        return (new PaymentMail())->getShortcodes($payment);
    }

    public static function getOrderCustomerShortCodes(OrderCustomer $orderCustomer = null): array
    {
        return (new OrderCustomerMail())->getShortcodes($orderCustomer);
    }
}
