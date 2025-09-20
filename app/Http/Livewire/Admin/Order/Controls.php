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
use Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use App\Mail\OrderCustomMail;

/**
 * Popup menu used on the order screen
 *
 * @property Order $order
 */
class Controls extends ModalComponent
{
    use SendsEvents, WithFileUploads;

    public $listeners = [
        'sendBookingConfirmation' => 'sendBookingConfirmation',
        'sendPaymentDueMail' => 'sendPaymentDue',
        'sendPaymentMail' => 'sendPaymentMade',
        'sendReservationToEmail' => 'sendReservationToEmail',
        'sendItineraryToEmail' => 'sendItineraryToEmail',
    ];

    public Order|int $order;

    // Form fields
    public $fromEmail;
    public $fromName;
    public $to;
    public $ccInput = '';
    public $bccInput = '';
    public $subject;
    public $emailBody;
    public $additionalAttachment;
    public $orderData;

    // UI state
    public $showModal = false;
    public $isSending = false;
    public $successMessage = '';
    public $errorMessage = '';

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




    protected $rules = [
        'fromEmail' => 'required|email',
        'fromName' => 'required|string',
        'to' => 'required|email',
        'ccInput' => 'nullable|string',
        'bccInput' => 'nullable|string',
        'subject' => 'required|string|max:255',
        'emailBody' => 'required|string',
        'additionalAttachment' => 'nullable|file|max:2048|mimes:pdf,doc,docx',
    ];

    public function openPopupEmailForm(): void
    {
        $defaultEmail = config('mail.from.address', config('mail.mailers.smtp.username', 'info@octopustravelmatrix.com'));
        $defaultName = config('mail.from.name', setting('company.name', 'Octopus Travel Matrix'));

        $user = Auth::user();
        $leadBooker = $this->order?->leadBooker?->customer?->last_name;
        $this->fromEmail = $this->order->consultant?->email ?? $user->email ?? $defaultEmail;
        $this->fromName = $this->order->consultant?->name ?? $user->name ?? $defaultName;
        $this->to = $this->order->agent?->email ?? $this->order->organization?->contact_email ?? $this->order->leadBooker->customer->email_address;
        $eventName = $this->order?->tour?->event?->name;
        $this->subject = "Order: " . ($eventName ? "{$eventName}" : '') . " - " .$this->order->booking_reference. ($leadBooker ? " - {$leadBooker}" : '');
        $this->emailBody = ($this->order?->tour?->event?->itinerary_email_template !== null) ? $this->order?->tour?->event?->itinerary_email_template : setting("email.itinerary-document.template", '');
        $this->bccInput = setting('system.bcc.mail', '');
        $this->ccInput = setting('system.cc.mail', '');
        $this->showModal = true;
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['additionalAttachment', 'ccInput', 'bccInput', 'successMessage', 'errorMessage']);
        $this->resetErrorBag();
        $this->isSending = false;
    }

    public function sendEmail(): void
    {
        $this->validate();
        $this->isSending = true;
        $this->successMessage = '';
        $this->errorMessage = '';

        $ccEmails = $this->parseEmails($this->ccInput);
        $bccEmails = $this->parseEmails($this->bccInput);

        if (!$this->validateEmailList($ccEmails, 'ccInput', 'cc')) return;
        if (!$this->validateEmailList($bccEmails, 'bccInput', 'bcc')) return;

        $fullPath = null;
        try {
            $this->orderData = dompdf(view('pdf.invoices.itinerary', ['order' => $this->order, 'itinerary' => $this->order->repository->getItinerary(),]), false);
            if ($this->additionalAttachment) {
                $fullPath = $this->additionalAttachment->getRealPath();
            }

            $mail = new OrderCustomMail(
                Auth::user(),
                $this->order,
                $this->subject,
                $this->emailBody,
                $this->orderData,
                $fullPath,
                $ccEmails,
                $bccEmails,
                $this->fromEmail,
                $this->fromName
            );
            Mail::to($this->to)->send($mail);
            $this->successMessage = 'The itinerary document has been successfully sent to the recipient ' . $this->to;
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to send email: ' . $e->getMessage();
            $this->isSending = false;
        } finally {
            $this->isSending = false;
        }
        $this->isSending = false;
    }

    public function render()
    {
        return view('livewire.admin.order.controls');
    }

    private function parseEmails(?string $input): array
    {
        return collect(explode(';', $input ?? ''))
            ->map(fn($email) => trim($email))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function validateEmailList(array $emails, string $inputName, string $fieldAlias): bool
    {
        $validator = Validator::make([$inputName => $emails], [
            "{$inputName}.*" => 'nullable|email',
        ]);

        if ($validator->fails()) {
            $this->addError($inputName, "One or more " . strtoupper($fieldAlias) . " emails are invalid.");
            $this->isSending = false;
            return false;
        }

        return true;
    }

}
