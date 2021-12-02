<?php

namespace App\Repository;

use App\Models\Address;
use App\Models\Country;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\Order;
use Illuminate\Http\Request;

interface MailRepositoryInterface
{
    public static function getBookingConfirmationBody($order);
    public static function getEmailTemplate(string $name);
}

class MailRepository implements MailRepositoryInterface
{
    public static function getBookingConfirmationBody($order)
    {
        $fillables = ShortCodeRepository::getOrderShortCodes($order);
        $template = self::getEmailTemplate('email.booking.confirmation');
        foreach ($fillables as $key => $value) {
            $template = str_replace('['.$key.']', $value, $template);
        }
        return $template;
    }

    public static function getPaymentDueBody($payment)
    {
        $fillables = ShortCodeRepository::getPaymentShortCodes($payment);
        $template = self::getEmailTemplate('email.payment.due');
        foreach ($fillables as $key => $value) {
            $template = str_replace('['.$key.']', $value, $template);
        }
        return $template;
    }

    public static function getPaymentMadeBody($payment)
    {
        $fillables = ShortCodeRepository::getPaymentShortCodes($payment);
        $template = self::getEmailTemplate('email.payment.made');
        foreach ($fillables as $key => $value) {
            $template = str_replace('['.$key.']', $value, $template);
        }
        return $template;
    }

    public static function getRefundGivenBody($payment)
    {
        $fillables = ShortCodeRepository::getPaymentShortCodes($payment);
        $template = self::getEmailTemplate('email.refund.given');
        foreach ($fillables as $key => $value) {
            $template = str_replace('['.$key.']', $value, $template);
        }
        return $template;
    }

    public static function getEmailTemplate(string $name)
    {
        return SettingsRepository::getOrDefault($name, 'This template has not been set up yet');
    }
}
