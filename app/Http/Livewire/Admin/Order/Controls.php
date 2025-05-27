<?php

namespace App\Http\Livewire\Admin\Order;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Order;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use Exception;
use LivewireUI\Modal\ModalComponent;
use Log;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Popup menu used on the order screen
 *
 * @property Order $order
 */
class Controls extends ModalComponent
{
    use SendsEvents;

    public $listeners = [
        'sendBookingConfirmation' => 'sendBookingConfirmation',
        'sendPaymentDueMail' => 'sendPaymentDue',
        'sendPaymentMail' => 'sendPaymentMade',
        'sendReservationToEmail' => 'sendReservationToEmail',
        'sendItineraryToEmail' => 'sendItineraryToEmail',
    ];

    public Order|int $order;

    /**
     * Mount the component and setup data
     * @param Order|int $order
     * @return void
     */
    public function mount(Order|int $order): void
    {
        $this->order = Order::getForMount($order);
    }

    /**
     * Download a PDF copy of the reservation document
     */
    public function getReservation(): StreamedResponse
    {
        return dompdf(view('pdf.quotes.itinerary', ['itinerary' => $this->order->repository->getReservationDocument(),]));
    }

    /**
     * Send the order reservation document to the email
     */
    public function sendReservationToEmail(): bool
    {
        try {
            $this->order->repository->mailer(true)->sendReservationEmail();
            $this->toast('Mail Sent Successfully', 'Successfully sent the reservation document', 'success');
            return true;
        } catch (MailDisabledException) {
            $this->toast('Mail Failed To Send', 'Sending Emails is disabled on this system', 'danger');
            return false;
        } catch (MailFailedException $e) {
            $this->toast('Mail Failed To Send', $e->getMessage(), 'danger');
            return false;
        } catch (Exception $e) {
            $this->toast('Mail Failed To Send', 'Please try again later', 'danger');
            Log::error($e);
            return false;
        }
    }


    /**
     * Send the order itinerary document to the email
     */
    public function sendItineraryToEmail(): bool
    {
        try {
            $this->order->repository->mailer(true)->sendItineraryEmail();
            $this->toast('Mail Sent Successfully', 'Successfully sent the itinerary document', 'success');
            return true;
        } catch (MailDisabledException) {
            $this->toast('Mail Failed To Send', 'Sending Emails is disabled on this system', 'danger');
            return false;
        } catch (MailFailedException $e) {
            $this->toast('Mail Failed To Send', $e->getMessage(), 'danger');
            return false;
        } catch (Exception $e) {
            $this->toast('Mail Failed To Send', 'Please try again later', 'danger');
            Log::error($e);
            return false;
        }
    }


    /**
     * Send the booking confirmation email
     * @return void
     */
    public function sendBookingConfirmation(): void
    {
        try {
            $success = $this->order->repository->mailer(true)->sendBookingConfirmation();
        } catch (MailFailedException $e) {
            $this->toast('Mail Failed To Send', $e->getMessage(), 'danger');
            return;
        }
        if ($success) {
            $this->toast('Mail Sent Successfully', 'Successfully sent the booking confirmation', 'success');
        } else {
            $this->toast('Mail Failed To Send', 'Please try again later', 'danger');
        }
    }

    public function sendPaymentDue(): void
    {
        try {
            $installment = $this->order->repository->getNextPaymentDetails();
            if ($installment === null) {
                $this->toast('Cannot Send Payment Mail', 'No installments are due or overdue', 'warning');
                return;
            }
            $final = $installment->id === 0;
            if ($installment->due_on->diffInDays(now(), false) < 0) {
                if ($final) {
                    $success = $this->order->repository->mailer(true)->sendFinalPaymentDue();
                } else {
                    $success = $this->order->repository->mailer(true)->sendPaymentDue();
                }
            } else {
                if ($final) {
                    $success = $this->order->repository->mailer(true)->sendFinalPaymentOverdue();
                } else {
                    $success = $this->order->repository->mailer(true)->sendPaymentOverdue();
                }
            }
        } catch (MailFailedException $e) {
            $this->toast('Mail Failed To Send', $e->getMessage(), 'danger');
            return;
        }
        if ($success) {
            $title = $this->getInstallmentTitle($installment);
            $this->order->last_manual_reminder = now();
            $this->order->save();
            $this->toast('Mail Sent Successfully', "Successfully sent the {$title} mail", 'success');
        } else {
            $this->toast('Mail Failed To Send', 'Please try again later', 'danger');
        }
    }

    public function getInstallmentTitle(OrderInstallment|null $installment = null): string|null
    {
        $installment = $installment ?? $this->order->repository->getNextPaymentDetails();
        if ($installment === null) { return null; }
        $final = $installment->id === 0;
        if ($installment->due_on->diffInDays(now(), false) < 0) {
            if ($final) {
                return "Final Payment Due";
            }
            return "Payment Due";
        }

        if ($final) {
            return "Final Payment Overdue";
        }
        return "Payment Overdue";
    }

    public function sendPaymentMade(): void
    {
        $payment = $this->order->payments()->orderBy('paid_on', 'desc')->first();
        try {
            if ($payment === null) {
                $this->toast('Mail Failed To Send', 'No payments have been made', 'warning');
                return;
            }
            if ($payment->amount >= 0) {
                $success = $this->order->repository->mailer(true)->sendPaymentMade();
            } else {
                $success = $this->order->repository->mailer(true)->sendRefundGiven();
            }
        } catch (MailFailedException $e) {
            $this->toast('Mail Failed To Send', $e->getMessage(), 'danger');
            return;
        }
        $title = $this->getPaymentTitle($payment);
        if ($success) {
            $this->toast('Mail Sent Successfully', "Successfully sent the {$title} mail", 'success');
        } else {
            $this->toast('Mail Failed To Send', 'Please try again later', 'danger');
        }
    }

    public function getPaymentTitle(Payment|null $payment = null): string|null
    {
        $payment = $payment ?? $this->order->payments()->orderBy('paid_on', 'desc')->first();
        if ($payment === null) { return null; }
        return $payment->amount >= 0 ? 'Payment' : 'Refund';
    }

    public function render()
    {
        return view('livewire.admin.order.controls');
    }
}
