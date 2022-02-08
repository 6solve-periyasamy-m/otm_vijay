<?php

namespace App\Repository;

use App\Mail\TemplatedMailable;
use App\Models\Order;
use App\Models\Payment;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class MailRepository
{
    /**
     * Get a list of available mail
     * @return array
     */
    public static function getAvailableMail(): array
    {
        return [
            'booking-confirmation' => ['template' => 'email.booking.confirmation','shortcodes' => 'order', ],
            'payment-due' => ['template' => 'email.payment.due', 'shortcodes' => 'order', ],
            'payment-overdue' => ['template' => 'email.payment.overdue', 'shortcodes' => 'order', ],
            'payment-made' => ['template' => 'email.payment.made', 'shortcodes' => 'payment', ],
            'refund-given' => ['template' => 'email.refund.given', 'shortcodes' => 'payment', ],
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
        return self::getAvailableMail()[$mail];
    }

    /**
     * Get the mail details for editing
     * @param string $mail The name of the mail
     * @return array{template:string,shortcodes:array}|null The mail body and shortcodes, or null if not exists
     */
    public static function getMailTemplate(string $mail): ?array
    {
        $info = self::getMailInformation($mail);
        if (isset($info)) return null;
        return ['template' => self::getEmailTemplate($info['key']), 'shortcodes' => ShortCodeRepository::getFromString($info['shortcodes']),];
    }

    /**
     * Send a mail to the currently logged-in user. Shortcut to the sendMailable method
     * @param string $mail The mail to send
     * @param Order|Payment|null $model The model to get data from
     * @return bool Was the mail sent?
     */
    public static function sendDemoMailable(string $mail, Model $model = null): bool
    {
        return self::sendMailable($mail, Auth::user()->email, $model);
    }

    /**
     * Send a mail to a specific email address.
     * @param string $mail The mail to send
     * @param string $email The email to send to
     * @param Order|Payment|null $model The model to get data from
     * @return bool Was the mail sent?
     */
    public static function sendMailable(string $mail, string $email, Model $model = null): bool
    {
        if (!self::doesTemplateExist($mail)) return false;
        $mailable = self::generateEmail($mail, $model);
        if (!isset($mailable)) return false;
        Mail::to($email)->send($mailable);
        return true;
    }

    /**
     * Generates a mailable, ready to be sent
     * @param string $mail The mail to send
     * @param Order|Payment|null $model The model to get data from
     * @return TemplatedMailable|null The generated mailable, ready to be sent, or null if $mail is invalid
     */
    public static function generateEmail(string $mail, Model $model): ?TemplatedMailable
    {

        switch (true) {
            case $model instanceof Order:
                return new TemplatedMailable(self::generateOrderEmail($mail, $model));
            case $model instanceof Payment:
                return new TemplatedMailable(self::generatePaymentEmail($mail, $model));
            default:
                return new TemplatedMailable(self::generateSettingsEmail($mail));
        }
    }

    /**
     * Generate a mail from an Order
     * @param string $mail The mail to be generated
     * @param Order $order The order to get details from
     * @return string The body of the mail
     */
    public static function generateOrderEmail(string $mail, Order $order): string
    {
        $body = self::getEmailTemplate($mail);
        $shortcodes = ShortCodeRepository::getOrderShortCodes($order);
        return self::replaceShortcodes($body, $shortcodes);
    }

    /**
     * Generate a mail from a Payment
     * @param string $mail The mail to be generated
     * @param Payment $payment The payment to get details from
     * @return string The body of the mail
     */
    public static function generatePaymentEmail(string $mail, Payment $payment): string
    {
        $body = self::getEmailTemplate($mail);
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
        $body = self::getEmailTemplate($mail);
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
     * Replace shortcodes with correct values
     * @param string $body The text to search
     * @param array $shortcodes The shortcodes to replace
     * @return string The body with shortcodes replaced
     */
    private static function replaceShortcodes(string $body, array $shortcodes): string
    {
        foreach ($shortcodes as $key => $value) {
            $body = str_replace('['.$key.']', $value, $body);
        }
        return $body;
    }

    /**
     * Get a template from the Settings Repository
     * @param string $name The template to get
     * @return string The template body
     *
     */
    private static function getEmailTemplate(string $name): string
    {
        return SettingsRepository::getOrDefault($name, 'This template has not been set up yet');
    }
}
