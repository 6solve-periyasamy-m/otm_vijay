<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Models\Helper\Enum\BookingTravellerRole;
use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Customer\Customer;
use App\Repository\Model\Booking\BookingTravellerRepository;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use Exception;
use App\Models\Quote\Quote;
use App\Mail\Storage\Attachment;
use App\Mail\Storage\QuoteMail;

class Guest extends V3BookingComponent
{
    private const MAX_TRAVELLERS = 5;
    public string $selectedCurrency;
    public array $availableCurrencies = ['AUD', 'USD', 'GBP', 'SGD', 'INR', 'EUR'];
    public bool $quoteSent = false;    
    protected array $messages = [
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
    ];

    public function mount($tour = null, $booking = null)
    {
        parent::mount($tour, $booking);
        $this->selectedCurrency = setting('system.currency');
    }

    public function convertedBasePrice(): float|null
    {
        return fx_convert($this->booking->repository->getBasePrice(), setting('system.currency'), $this->selectedCurrency);
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.guest');
    }

    public function back()
    {
        return; // First page, cannot go back
    }

    public function advance()
    {
        $this->validate();
        $this->convertLeadToCustomer();
        return redirect()->route('booking.v3.hotel', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }
    
    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function addTraveller(): void
    {
        if ($this->booking->travellers()->count() >= self::MAX_TRAVELLERS) { return; }
        $this->booking->repository->addUnknownTraveller();
        $this->render();
    }

    public function removeTraveller(): void
    {
        $this->booking->repository->removeUnknownTraveller();
        $this->render();
    }
  
    public function updated($property): void
    {
        if (str_starts_with($property, 'lead.')) {
            $this->validateOnly($property);
    
            // Only auto-convert if email is valid and set
            if ($property === 'lead.email_address' && !empty($this->lead->email_address)) {
                $this->convertLeadToCustomer();
            }
            $this->lead->save();
        }
    }

    public function convertLeadToCustomer(): ? Customer
    {
        $lead = $this->lead;

        if (empty($lead->email_address)) {
            Log::warning("Lead traveller missing email for booking ID {$this->booking->id}");
            return null;
        }

        try {
            $lead->first_name = $lead->first_name ?? 'Unset';
            $lead->last_name = $lead->last_name ?? 'Unset';
            $lead->save();

            $repository = new BookingTravellerRepository($lead);
            $customer = $repository->convertToCustomer();
            if (!$lead->customer_id || $lead->customer_id !== $customer->id) {
                $lead->customer_id = $customer->id;
                $lead->save();
            }

            return $customer;

        } catch (\Throwable $e) {
            Log::error("Failed to convert lead to customer", [
                'booking_id' => $this->booking->id,
                'lead_id' => $lead->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return null;
        }       
    }

    protected array $rules = [
        'lead.email_address' => 'required|email',
    ];


    public function emailQuote(): void
    {
        $this->validateOnly('lead.email_address');
        $leadTraveller = $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->first();
        $target = $leadTraveller?->email_address;

        if ($target === null) {
            session()->flash('error', 'Cannot send quote, no valid target email found.');
        }
        try {
            $this->quoteSent = true;
            //$quote = Quote::find(1529);
            $quote = $this->booking->repository->convertToQuote();
            $sent = $quote->repository->generateSent($target, $quote->paying?? 1, $quote->travelling?? 0);
            $attachment = new Attachment($quote->repository->getStream($sent), $quote->reference . '.pdf', ['mime' => 'application/pdf',]);
            $status = (new QuoteMail('quote', $quote->consultant))->send($target ?? $sent->recipient, $sent, [$attachment,], "", true);
        } catch (MailDisabledException) {
            session()->flash('error', 'Failed to Send Quote-Emails are not enabled on this system.');
            return;
        } catch (MailFailedException $e) {
            $status = false;
        } catch (Exception $e) {
            session()->flash('error', 'Failed to Send Quote-'.$e->getMessage());
            return;
        }
        if ($status ?? false) {
            session()->flash('success', 'Quote emailed successfully.');
        } else {
            session()->flash('error', 'Cannot send quote, no valid target email found.');
        }
    }

}