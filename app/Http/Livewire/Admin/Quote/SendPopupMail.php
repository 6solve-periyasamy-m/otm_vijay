<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Models\Quote\Quote;
use App\Models\Quote\SentQuote;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Mail\Storage\QuoteMail;
use App\Mail\Storage\Attachment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\QuoteCustomMail;
use Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class SendPopupMail extends Component
{
    use WithFileUploads;

    public Quote $quote;
    public $quoteId;
    public $paying;
    public $travelling;
    
    // Form fields
    public $fromEmail;
    public $fromName;
    public $to;
    public $ccInput = '';
    public $bccInput = '';
    public $subject;
    public $emailBody;
    public $additionalAttachment;
    public $quoteData;
    
    // UI state
    public $showModal = false;
    public $isSending = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'fromEmail' => 'required|email',
        'fromName' => 'required|string',
        'to' => 'required|email',
        'ccInput' => 'nullable|string',
        'bccInput' => 'required|string',
        'subject' => 'required|string|max:255',
        'emailBody' => 'required|string',
        'additionalAttachment' => 'nullable|file|max:2048|mimes:pdf,doc,docx',
    ];

    protected $listeners = ['openEmailModal' => 'openModal'];

    public function mount(Quote $quote)
    {
        $this->quote = $quote;
        $this->fromEmail = config('mail.from.address');
        $this->fromName = config('mail.from.name');
        $this->ccInput = setting('system.cc.mail', '');
        $this->bccInput = setting('system.bcc.mail', '');
        $this->emailBody = setting("email.quote.template", '');
    }

    public function openModal($quoteId, $paying, $travelling)
    {
        $this->quoteId = $quoteId;
        $this->paying = (int)$paying;
        $this->travelling = (int)$travelling;
        $leadTraveller = $this->quote?->leadTraveller?->customer->first_name;
        $this->fromEmail = $this->quote->consultant?->email ?? config('mail.from.address');
        $this->fromName = $this->quote->consultant?->name ?? config('mail.from.name');
        $this->to = $this->quote->agent?->email ?? $this->quote->organization?->contact_email ?? $this->quote->leadTraveller->customer->email_address;
        $eventName = $this->quote?->event?->name;
        $this->subject = "Quote: {$this->quote->reference}" . ($eventName ? " - {$eventName}" : '') . ($leadTraveller ? " - {$leadTraveller}" : '');

        // If email body is empty, use the default template
        $this->emailBody = setting("email.quote.template", '');    
        $this->successMessage = '';
        $this->errorMessage = '';    
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.admin.quote.send-popup-mail');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['additionalAttachment', 'ccInput', 'bccInput', 'successMessage', 'errorMessage']);
        $this->resetErrorBag();
        $this->isSending = false;
    }

    public function sendEmail()
    {
        $this->validate();
        $this->isSending = true;
        $this->successMessage = '';
        $this->errorMessage = '';
        $bccEmails = collect(explode(';', $this->bccInput))
            ->map(fn($email) => trim($email))
            ->filter()
            ->unique()
            ->toArray();

        $validator = Validator::make(['bcc' => $bccEmails], [
            'bcc.*' => 'nullable|email'
        ]);

        if ($validator->fails()) {
            $this->addError('bccInput', 'One or more BCC emails are invalid.');
            return;
        }
        $fullPath = null;
        try {
            $sentQuote = $this->quote->repository->generateSent($this->to, $this->paying, $this->travelling);            
            $this->quoteData = $this->quote->repository->getStream($sentQuote);

            if ($this->additionalAttachment) {
                $fullPath = $this->additionalAttachment->getRealPath();
            }            
            $sentQuote->from_email = $this->fromEmail;
            $sentQuote->from_name = $this->fromName;
            $sentQuote->bcc = $this->bccInput;
            $sentQuote->subject = $this->subject;
            $sentQuote->email_body = $this->emailBody;
            $sentQuote->additional_attachments = json_encode($fullPath);
            $sentQuote->save();

            $mail = new QuoteCustomMail(
                Auth::user(),
                $this->quote,
                $this->subject,
                $this->emailBody,
                $this->quoteData,
                $fullPath,
                $bccEmails
            );
            Mail::to($this->to)->send($mail);

            $sentQuote->mail_status = 'sent';
            $sentQuote->save();
            //sleep(2);
            $this->successMessage = 'The quote document has been successfully sent to the recipient ' . $this->to;
            // if ($fullPath && file_exists($fullPath)) {
            //     @unlink($fullPath);
            // }
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to send email: ' . $e->getMessage();
            $this->isSending = false;
        } finally {
            $this->isSending = false;
        }     
    }
}