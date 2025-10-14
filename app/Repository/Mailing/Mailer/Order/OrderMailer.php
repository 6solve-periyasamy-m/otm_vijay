<?php

namespace App\Repository\Mailing\Mailer\Order;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Mail\Storage\Attachment;
use App\Mail\Storage\OrderMail;
use App\Models\Order\Order;
use App\Models\Order\OrderInstallment;
use App\Repository\Model\Order\InvoiceRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
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
     * @param string|null $email Email to send to
     * @param OrderInstallment|null $next Next Installment, if you've already fetched it
     * @return bool Did the mail send successfully?
     * @throws MailFailedException
     */
    public function sendReminderMail(string $email = null, OrderInstallment|null $next = null): bool
    {
        $next = $next ?? $this->order->repository->getNextPaymentDetails();

        if (($next === null) || ($next->remaining < setting('order.reminders.minimum', 1.0))) { return false; }

        if ($next->due_on->isAfter(now())) {
            if ($next->id === null || $next->id === 0) {
                return $this->sendFinalPaymentDue($email);
            }
            return $this->sendPaymentDue($email);
        }

        if ($next->id === null || $next->id === 0) {
            return $this->sendFinalPaymentOverdue($email);
        }

        return $this->sendPaymentOverdue($email);
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
     * @throws MailFailedException
     */
    public function sendReservationEmail(string $email = null, bool $sendAsConsultant = false): bool
    {
        $email = $email ?? $this->order->agent?->email ?? $this->order->organization?->contact_email ?? $this->order->leadBooker->customer->email_address ;

        $invoice = $this->order->repository->getInvoiceRepository()->invoice;
        $invoice->payment_schedule = $this->order->repository->getScheduleItineraryArray();
        $invoice->organization = $order->organization ?? null;
        $invoice->agent = $order->agent ?? null;

        $bcc = flag('mail.bcc-consultant', false) ? $this->order->consultant?->email : "";

        try {
            (new OrderMail('reservation-invoice-document', $sendAsConsultant ? $this->order->consultant : null))
                    ->send($email, $this->order, [$this->getReservationAttachment(), $this->getInvoiceAttachment()], $bcc, true, $this->order->consultant?->email);
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


    public function sendItineraryEmail(string $email = null, bool $sendAsConsultant = false): bool
    {
        $user = $sendAs ?? Auth::user();
        $customFromEmail = $user->email;
        $customFromName = $user->name ?? $user->email;
        $email = $email ?? $this->order->agent?->email ?? $this->order->organization?->contact_email ?? $this->order->leadBooker->customer->email_address ;
        $bcc = flag('mail.bcc-consultant', false) ? $this->order->consultant?->email : "";
        try {
            $mail = (new OrderMail('itinerary-document', $sendAsConsultant ? $this->order->consultant : null, $customFromEmail, $customFromName));
            if ($this->order?->tour?->event?->itinerary_email_template !== null) { $mail->setBody($this->order?->tour?->event?->itinerary_email_template); }
            if ($this->order?->tour?->event?->itinerary_email_subject !== null) { $mail->setSubject($this->order?->tour?->event?->itinerary_email_subject); }
            $mail->setSender($customFromEmail, $customFromName);
            $mail->send($email, $this->order, [$this->getItineraryAttachment()], $bcc, true);
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

    /**
     * Send any coded mail related to the order. Refer to \App\Repository\Mailing\MailRepository::getAvailableMail for valid codes
     * @param string $code The mail code to use
     * @param string|null $email Email to send the mail to. Defaults to lead booker email if null
     * @param bool $ignoreConsultantFlag Should the setting for bcc consultant be ignored. Defaults to false
     * @param bool $sendAsConsultant Should the email be sent using the consultants email instead of the users email
     * @param Attachment[] $attachments List of attachments to include with the mail
     * @return bool Was the mail sent successfully
     * @throws MailFailedException
     */
    public function sendMail(string $code, string|null $email = null, bool $ignoreConsultantFlag = false, bool $sendAsConsultant = false, array $attachments = []): bool
    {
        $bcc = (!($ignoreConsultantFlag) && flag('mail.bcc-consultant', false)) ? $this->order->consultant->email . ";" . (setting('system.bcc.mail') ?? "") : "";
        if ($email === null) {
            $email = $this->order->agent?->email ??
                        $this->order->organization?->contact_email ??
                        $this->order->leadBooker->customer->email_address;
        }
        try {
            (new OrderMail($code, $sendAsConsultant ? $this->order->consultant : null))->send($email, $this->order, [], $bcc, $this->force);
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

    private function getInvoiceAttachment(): Attachment
    {
        $invoice = $this->order->repository->getInvoiceRepository()->invoice;
        // TODO: Implement a better solution for this.
        $invoice->payment_schedule = $this->order->repository->getScheduleItineraryArray();
        $invoice->organization = $this->order->organization ?? null;
        $invoice->agent = $this->order->agent ?? null;
        return new Attachment((new InvoiceRepository($invoice))->getResponseStream(false), 'Invoice_'.$this->order->booking_reference.'.pdf', ['mime' => 'application/pdf',]);
    }

    private function getReservationAttachment(): Attachment
    {
        $document = dompdf(view('pdf.quotes.itinerary', ['itinerary' => $this->order->repository->getReservationDocument(), 'type' => 'Reservation']), false);
        return new Attachment($document, 'Reservation_'.$this->order->booking_reference.'.pdf', ['mime' => 'application/pdf',]);
    }



    private function getItineraryAttachment(): Attachment
    {
        $document = dompdf(view('pdf.invoices.itinerary', ['order' => $this->order, 'itinerary' => $this->order->repository->getItinerary(),]), false);
        return new Attachment($document, 'Itinerary_'.$this->order->booking_reference.'.pdf', ['mime' => 'application/pdf',]);
    }

}
