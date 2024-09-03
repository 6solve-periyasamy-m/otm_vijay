<?php

namespace App\Repository\Mailing\Mailer\Order;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Mail\Storage\OrderMail;
use App\Models\Order\Order;
use Log;

class OrderMailer
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @throws MailFailedException
     */
    public function sendBookingConfirmation(string $email = null): bool
    {
        return $this->sendMail('booking-confirmation', $email);
    }

    /**
     * @throws MailFailedException
     */
    public function sendPaymentDue(string $email = null): bool
    {
        return $this->sendMail('payment-due', $email);
    }

    /**
     * @throws MailFailedException
     */
    public function sendPaymentOverdue(string $email = null): bool
    {
        return $this->sendMail('payment-overdue', $email);
    }

    /**
     * @throws MailFailedException
     */
    public function sendFinalPaymentDue(string $email = null): bool
    {
        return $this->sendMail('final-payment-due', $email);
    }

    /**
     * @throws MailFailedException
     */
    public function sendFinalPaymentOverdue(string $email = null): bool
    {
        return $this->sendMail('final-payment-overdue', $email);
    }

    /**
     * @throws MailFailedException
     */
    public function sendMail(string $code, string|null $email = null): bool
    {
        $bcc = flag('mail.bcc-consultant', false) ? $this->order->consultant->email : "";
        if ($email === null) {
            $email = $this->order->leadBooker->customer->email_address;
        }
        try {
            (new OrderMail($code))->send($email, $this->order, [], $bcc);
            return true;
        } catch (MailDisabledException) {
            return false;
        } catch (MailFailedException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
