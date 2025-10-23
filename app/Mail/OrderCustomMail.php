<?php

namespace App\Mail;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;
use PDF;

class OrderCustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $subjectLine;
    public $htmlBody;
    public $sentData;
    public $documentPaths;
    public $bccEmails;
    public $ccEmails;
    public $fromEmail;
    public $fromName;

    public function __construct($user, Order $order, $subjectLine, $htmlBody, $sentData, $documentPaths, $ccEmails=[], $bccEmails=[], $fromEmail, $fromName)
    {
        $this->user = $user;
        $this->order = $order;
        $this->subjectLine = $subjectLine;
        $this->htmlBody = $htmlBody;
        $this->sentData = $sentData;
        $this->documentPaths = $documentPaths;
        $this->ccEmails = $ccEmails;
        $this->bccEmails = $bccEmails;
        $this->fromEmail = $fromEmail;
    }

    public function build()
    {
        $customFromEmail = !empty($this->fromEmail) ? $this->fromEmail : $this->user->email;
        $customFromName = !empty($this->fromName) ? $this->fromName : $this->user->name;
        $body = $this->replaceShortcodes($this->htmlBody, $this->getShortcodes());
        $subject = $this->replaceShortcodes($this->subjectLine, $this->getShortcodes());
        $mail = $this->from($customFromEmail, $customFromName)
            ->subject($subject)
            ->view('mail.order-template')
            ->with([
                'user' => $this->user,
                'order' => $this->order,
                'bodyContent' => $body,
            ])
            ->attachData($this->sentData, "Itinerary-{$this->order->booking_reference}.pdf");

        foreach ($this->documentPaths as $filePath) {
            $mail->attach($filePath);
        }

        if (!empty($this->ccEmails)) {
            $mail->cc($this->ccEmails);
        }

        if (!empty($this->bccEmails)) {
            $mail->bcc($this->bccEmails);
        }
        return $mail;
    }

    protected function getShortcodes(): array
    {
        $customer = $this->order?->leadBooker->customer;
        $nextPayment = $this->order?->next_installment;
        $finalPayment = $this->order?->repository->generateRemainingOrderInstallment();
        $tour = $this->order?->tour;

        return [
            'LEAD_TITLE' => $customer?->title ?? '',
            'LEAD_FIRST_NAME' => $customer->first_name ?? '',
            'LEAD_MIDDLE_NAMES' => $customer->middle_names ?? '',
            'LEAD_LAST_NAME' => $customer->last_name ?? '',
            'LEAD_PASSPORT_EXPIRY_DATE' => f_date($customer->passport_expiry_date ?? ''),
            'LEAD_CONTACT_NAME' => $this->order?->agent?->first_name ?? $order?->leadBooker?->customer?->first_name ??  '',
            'BOOKING_REFERENCE' => $this->order?->booking_reference ?? '',
            'ORDERED_ON' => f_date($this->order?->ordered_on ?? ''),
            'ORDER_COST' => f_currency($this->order?->cost ?? 0),
            'TOTAL_OWED' => f_currency($this->order?->total ?? 0),
            'DEPOSIT' => f_currency($this->order?->calculated_deposit ?? 0),
            'TOTAL_PAID' => f_currency($this->order?->paid ?? 0),
            'TOTAL_REMAINING' => f_currency($this->order?->remaining ?? 0),
            'DUE_PAYMENT_TOTAL' => f_currency(isset($this->order) ? $nextPayment?->calculated_amount : 0),
            'DUE_PAYMENT_REMAINING' => f_currency(isset($this->order) ? $nextPayment?->remaining : 0),
            'DUE_PAYMENT_DATE' => f_date(isset($this->order) ? $nextPayment?->due_on : ''),
            'FINAL_PAYMENT_TOTAL' => f_currency(isset($this->order) ? $finalPayment?->calculated_amount : 0),
            'FINAL_PAYMENT_REMAINING' => f_currency(isset($this->order) ? $finalPayment?->remaining : 0),
            'FINAL_PAYMENT_DATE' => f_date(isset($this->order) ? $finalPayment?->due_on : ''),
            'TOUR_NAME' => $tour?->name ?? '',
            'EVENT_NAME' => isset($tour) ? $tour->event?->name : '',
            'EVENT_TYPE' => $tour && $tour->event && $tour->event->event_category ? ($tour->event->event_category->name === 'NORMAL' ? 'Child Event' : 'Parent Event'): '',
            'TOUR_DESCRIPTION' => $tour?->description ?? '',
            'TOUR_START' => f_date($tour?->date_from ?? ''),
            'TOUR_END' => f_date($tour?->date_to ?? ''),
            'TOUR_BASE_PER_PERSON' => f_currency($tour?->base_price_per_person ?? 0),
            'TOUR_SURCHARGE' => f_currency($tour?->single_occupancy_surcharge ?? 0),
            'LATEST_INVOICE' => route('customer.invoice', ['reference' => $order?->booking_reference ?? 'reference',]),
            'PORTAL_LINK' => route('customer.portal'),
            'ATOL_LINK' => route('customer.atol', ['reference' => $order?->booking_reference ?? 'reference',]),
            'DETAILS_LINK' => route('customer.edit'),
            'CURRENT_USER_NAME' => $this->user?->name ?? 'Staff Member',
            'CURRENT_USER_EMAIL' => $this->user?->email ?? 'noreply@kpt.com.au',
            'SETTING_COMPANY_NAME' => setting('company.name'),
            'SETTING_LOGO_URL' => asset(setting('company.logo')),
            'SETTING_COMPANY_ADDRESS_LINE_1' => setting('company.address.line_1'),
            'SETTING_COMPANY_ADDRESS_LINE_2' => setting('company.address.line_2'),
            'SETTING_COMPANY_ADDRESS_CITY' => setting('company.address.city'),
            'SETTING_COMPANY_ADDRESS_REGION' => setting('company.address.region'),
            'SETTING_COMPANY_ADDRESS_COUNTRY' => setting('company.address.country'),
            'SETTING_COMPANY_CONTACT_EMAIL' => setting('company.contact.email'),
            'SETTING_COMPANY_CONTACT_PHONE' => setting('company.contact.phone'),
            'SETTING_COMPANY_VAT' => setting('company.vat'),
            'SETTING_BOOKING_PREFIX' => setting('booking.prefix'),
            'SETTING_ATOL_ISSUER' => setting('atol.issuer'),
            'SETTING_ATOL_NUMBER' => setting('atol.number'),
            'SETTING_ATOL_STAMP' => asset(setting('atol.stamp')),
            'SETTING_COMPANY_URL' => setting('company.url'),
            'CURRENT_USER_IMAGE_URL' => $this->user?->avatar_url ?? 'No User Found',
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
