<?php

namespace App\Repository\Mailing\Mailer\Order;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Mail\Storage\OrderMail;
use App\Models\Order\Order;
use Exception;
use Log;

class OrderMailer
{
    private Order $order;
    private bool $force;

    /**
     * @param Order $order Which order should the mailer be for
     * @param bool $force Should the mailer ignore the disable mail setting
     */
    public function __construct(Order $order, bool $force = false)
    {
        $this->order = $order;
        $this->force = $force;
    }

    /**
     * Sends a booking confirmation email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendBookingConfirmation(string $email = null): bool
    {
        return $this->sendMail('booking-confirmation', $email);
    }

    /**
     * Sends a Payment Due email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendPaymentDue(string $email = null): bool
    {
        return $this->sendMail('payment-due', $email, true);
    }

    /**
     * Sends a Payment Overdue email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendPaymentOverdue(string $email = null): bool
    {
        return $this->sendMail('payment-overdue', $email, true);
    }

    /**
     * Sends a Final Payment Due email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendFinalPaymentDue(string $email = null): bool
    {
        return $this->sendMail('final-payment-due', $email, true);
    }

    /**
     * Sends a Final Payment Overdue email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendFinalPaymentOverdue(string $email = null): bool
    {
        return $this->sendMail('final-payment-overdue', $email, true);
    }

    /**
     * Sends a payment made email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendPaymentMade(string $email = null): bool
    {
        return $this->sendMail('payment-made', $email);
    }

    /**
     * Sends a Refund Given email for the order
     * @param string|null $email Email to send the mail to. Defaults to lead booker if null
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendRefundGiven(string $email = null): bool
    {
        return $this->sendMail('refund-given', $email);
    }

    /**
     * Send any coded mail related to the order. Refer to \App\Repository\Mailing\MailRepository::getAvailableMail for valid codes
     * @param string $code The mail code to use
     * @param string|null $email Email to send the mail to. Defaults to lead booker email if null
     * @param bool $ignoreConsultantFlag Should the setting for bcc consultant be ignored. Defaults to false
     * @return bool Was the mail sent successfully
     * @throws MailFailedException
     */
    public function sendMail(string $code, string|null $email = null, bool $ignoreConsultantFlag = false): bool
    {
        $bcc = (!($ignoreConsultantFlag) && flag('mail.bcc-consultant', false)) ? $this->order->consultant->email : "";
        if ($email === null) {
            $email = $this->order->leadBooker->customer->email_address;
        }
        try {
            (new OrderMail($code))->send($email, $this->order, [], $bcc, $this->force);
            return true;
        } catch (MailDisabledException) {
            return false;
        } catch (MailFailedException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
