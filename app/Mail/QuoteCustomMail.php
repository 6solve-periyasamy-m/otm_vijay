<?php

namespace App\Mail;

use App\Models\Quote\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;
use PDF;

class QuoteCustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $quote;
    public $subjectLine;
    public $htmlBody;
    public $sentData;
    public $documentPath;
    public $bccEmails;

    public function __construct($user, Quote $quote, $subjectLine, $htmlBody, $sentData, $documentPath, $bccEmails=[])
    {
        $this->user = $user;
        $this->quote = $quote;
        $this->subjectLine = $subjectLine;
        $this->htmlBody = $htmlBody;
        $this->sentData = $sentData;
        $this->documentPath = $documentPath;
        $this->bccEmails = $bccEmails;
    }

    public function build()
    {
        $body = $this->replaceShortcodes($this->htmlBody, $this->getShortcodes());
        $mail = $this->from(config('mail.from.address'))
            ->subject($this->subjectLine)
            ->view('mail.quote-template')
            ->with([
                'user' => $this->user,
                'quote' => $this->quote,
                'bodyContent' => $body,
            ])
            ->attachData($this->sentData, "quote-{$this->quote->reference}.pdf");

        if (!empty($this->documentPath) && file_exists($this->documentPath) && is_readable($this->documentPath)) {
            $mail->attach($this->documentPath);
        }

        if (!empty($this->bccEmails)) {
            $mail->bcc($this->bccEmails);
        }
        return $mail;
    }

    protected function getShortcodes(): array
    {

        $customer = $this->quote?->leadTraveller?->customer;

        return [
            'LEAD_CONTACT_NAME' => $this->quote?->agent?->name ?? $this->quote?->organization?->name ?? $this->quote?->leadTraveller?->name ?? 'Customer',
            'LEAD_TITLE' => $customer?->title ?? '',
            'LEAD_FIRST_NAME' => $customer?->first_name ?? '',
            'LEAD_MIDDLE_NAMES' => $customer?->middle_names ?? '',
            'LEAD_LAST_NAME' => $customer?->last_name ?? '',
            'BOOKING_REFERENCE' => $this->quote?->ref ?? 'OTM1234567890XXXX',
            'TOTAL_COST' => f_currency($this->quote?->repository->getTotalCost($this->quote?->paying ?? true) ?? 0),
            'EXPIRY' => f_date($this->quote?->expires ?? now()),
            'FINAL_PAYMENT' => f_date($this->quote?->final_payment ?? now()),
            'EVENT_NAME' => $this->quote?->event?->name ?? '',
            'CURRENT_USER_NAME' => auth()->user()?->name ?? 'Staff Member',
            'CURRENT_USER_EMAIL' => auth()->user()?->email ?? 'noreply@kpt.com.au',
        ];
    }

    protected function replaceShortcodes(string $content, array $shortcodes): string
    {
        foreach ($shortcodes as $key => $value) {
            $content = str_replace("[$key]", e($value), $content);
        }
        return $content;
    }    
}
