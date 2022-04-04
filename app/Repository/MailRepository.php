<?php

namespace App\Repository;

use App\Mail\TemplatedMailable;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Log;

class MailRepository
{
    /**
     * Get a list of available mail
     * @return array
     */
    public static function getAvailableMail(): array
    {
        return [
            'booking-confirmation' => 'order',
            'payment-due' => 'order',
            'payment-overdue' => 'order',
            'payment-made' => 'payment',
            'refund-given' => 'payment',
            'additional-traveller-added' => 'order-customer',
            'additional-traveller-removed' => 'order-customer',
            'order-changed' => 'order',
            'order-cancelled' => 'order',
        ];
    }

    /**
     * Verify a specific mail exists
     * @param string $mail The name of the mail
     * @return bool Whether the mail exists
     */
    public static function doesTemplateExist(string $mail): bool
    {
        return array_key_exists($mail, self::getAvailableMail());
    }

    /**
     * Get the basic information about a specific mailable
     * @param string $mail The name of the mail
     * @return array{template:string,shortcodes:string}|null The mail information, or null if not exists
     */
    public static function getMailInformation(string $mail): ?array
    {
        if (!self::doesTemplateExist($mail)) return null;
        return ['template' => "email.{$mail}.template", 'shortcodes' => self::getAvailableMail()[$mail], 'subject' => "email.{$mail}.subject"];
    }

    /**
     * Get the mail details for editing
     * @param string $mail The name of the mail
     * @return array{template:string,shortcodes:array}|null The mail body and shortcodes, or null if not exists
     */
    public static function getMailTemplate(string $mail): ?array
    {
        $info = self::getMailInformation($mail);
        if (!isset($info)) return null;
        return ['template' => self::getTemplateFromSettings($info['template']),
                'shortcodes' => ShortCodeRepository::getFromString($info['shortcodes']),
                'subject' => self::getTemplateFromSettings($info['subject'])];
    }

    /**
     * Send a mail to the currently logged-in user. Shortcut to the sendMailable method
     * @param string $mail The mail to send
     * @param Order|Payment|null $model The model to get data from
     * @return bool Was the mail sent?
     */
    public static function sendDemoMailable(string $mail, ?Model $model = null): bool
    {
        return self::sendMailable($mail, Auth::user()->email, $model);
    }

    /**
     * Send a mail to a specific email address.
     * @param string $mail The mail to send
     * @param string $email The email to send to
     * @param Order|Payment|OrderCustomer|null $model The model to get data from
     * @return bool Was the mail sent?
     */
    public static function sendMailable(string $mail, string $email, ?Model $model = null): bool
    {
        if (!self::doesTemplateExist($mail)) return false;
        $mailable = self::generateEmail($mail, $model);
        if (!isset($mailable)) return false;
        try {
            Mail::to($email)->send($mailable);
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Generates a mailable, ready to be sent
     * @param string $mail The mail to send
     * @param Order|Payment|null $model The model to get data from
     * @return TemplatedMailable|null The generated mailable, ready to be sent, or null if $mail is invalid
     */
    public static function generateEmail(string $mail, ?Model $model): ?TemplatedMailable
    {
        switch (true) {
            case $model instanceof Payment:
            case !isset($model): // If using demo data, fill ALL available shortcodes for demonstration purposes
                return new TemplatedMailable(self::generatePaymentSubject($mail, $model), self::generatePaymentEmail($mail, $model));
            case $model instanceof Order:
                return new TemplatedMailable(self::generateOrderSubject($mail, $model), self::generateOrderEmail($mail, $model));
            case $model instanceof OrderCustomer:
                return new TemplatedMailable(self::generateOrderCustomerSubject($mail, $model), self::generateOrderCustomerEmail($mail, $model));
            default:
                return new TemplatedMailable(self::generateSettingsSubject($mail), self::generateSettingsEmail($mail));
        }
    }

    /**
     * Generate a mail from an Order
     * @param string $mail The mail to be generated
     * @param Order|null $order The order to get details from
     * @return string The body of the mail
     */
    public static function generateOrderEmail(string $mail, ?Order $order): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['template']);
        $shortcodes = ShortCodeRepository::getOrderShortCodes($order);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a mail from an Order
     * @param string $mail The mail to be generated
     * @param OrderCustomer|null $orderCustomer The order to get details from
     * @return string The body of the mail
     */
    public static function generateOrderCustomerEmail(string $mail, ?OrderCustomer $orderCustomer): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['template']);
        $shortcodes = ShortCodeRepository::getOrderCustomerShortCodes($orderCustomer);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a mail from a Payment
     * @param string $mail The mail to be generated
     * @param Payment|null $payment The payment to get details from
     * @return string The body of the mail
     */
    public static function generatePaymentEmail(string $mail, ?Payment $payment): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['template']);
        $shortcodes = ShortCodeRepository::getPaymentShortCodes($payment);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a mail using only Settings for shortcodes
     * @param string $mail The mail to be generated
     * @return string The body of the mail
     */
    public static function generateSettingsEmail(string $mail): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['template']);
        $shortcodes = ShortCodeRepository::getSettingShortCodes();
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a subject from an Order
     * @param string $mail The mail to be generated
     * @param Order|null $order The order to get details from
     * @return string The body of the mail
     */
    public static function generateOrderSubject(string $mail, ?Order $order): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['subject']);
        $shortcodes = ShortCodeRepository::getOrderShortCodes($order);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a subject from an Order
     * @param string $mail The mail to be generated
     * @param OrderCustomer|null $orderCustomer The order to get details from
     * @return string The body of the mail
     */
    public static function generateOrderCustomerSubject(string $mail, ?OrderCustomer $orderCustomer): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['subject']);
        $shortcodes = ShortCodeRepository::getOrderCustomerShortCodes($orderCustomer);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a subject from a Payment
     * @param string $mail The mail to be generated
     * @param Payment|null $payment The payment to get details from
     * @return string The body of the mail
     */
    public static function generatePaymentSubject(string $mail, ?Payment $payment): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['subject']);
        $shortcodes = ShortCodeRepository::getPaymentShortCodes($payment);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a subject using only Settings for shortcodes
     * @param string $mail The mail to be generated
     * @return string The body of the mail
     */
    public static function generateSettingsSubject(string $mail): string
    {
        $body = self::getTemplateFromSettings(self::getMailInformation($mail)['subject']);
        $shortcodes = ShortCodeRepository::getSettingShortCodes();
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Update a mail template
     * @param string $mail The mail template to update
     * @param string $body The new body of the template
     * @return bool Whether the template was successfully updated
     */
    public static function updateMailTemplate(string $mail, string $body): bool
    {
        $info = self::getMailInformation($mail);
        if (!isset($info)) return false;
        SettingsRepository::set($info['template'], $body);
        return true;
    }

    /**
     * Update a mail subject
     * @param string $mail The mail template to update
     * @param string $subject The new body of the template
     * @return bool Whether the template was successfully updated
     */
    public static function updateMailSubject(string $mail, string $subject): bool
    {
        $info = self::getMailInformation($mail);
        if (!isset($info)) return false;
        SettingsRepository::set($info['subject'], $subject);
        return true;
    }

    /**
     * Replace shortcodes with correct values
     * @param string $body The text to search
     * @param array $shortcodes The shortcodes to replace
     * @return string The body with shortcodes replaced
     */
    private static function replaceShortcodes(string $body, array $shortcodes): string
    {
        $replacement = $body;
        foreach ($shortcodes as $key => $value) {
            $replacement = str_replace('['.$key.']', $value, $replacement);
        }
        return $replacement;
    }

    /**
     * Get a template from the Settings Repository
     * @param string $name The template to get
     * @return string The template body
     *
     */
    private static function getTemplateFromSettings(string $name): string
    {
        return SettingsRepository::getOrDefault($name, 'This template has not been set up yet');
    }
}
