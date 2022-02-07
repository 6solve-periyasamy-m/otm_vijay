<?php

namespace App\Repository;

class MailRepository
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

    public static function getPaymentOverdueBody($payment)
    {
        $fillables = ShortCodeRepository::getPaymentShortCodes($payment);
        $template = self::getEmailTemplate('email.payment.overdue');
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
