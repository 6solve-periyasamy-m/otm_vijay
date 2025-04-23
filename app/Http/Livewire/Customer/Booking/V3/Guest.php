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
use App\Models\Booking\BookingTraveller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Guest extends V3BookingComponent
{
    private const MAX_TRAVELLERS = 5;
    protected $listeners = ['currencyUpdated' => 'updateCurrency'];
    public bool $quoteSent = false;
    public bool $showCustomerForm = false;
    
    protected array $messages = [
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
    ];
    public BookingTraveller|null $lead = null;

    public function mount($tour = null, $booking = null)
    {        
        parent::mount($tour, $booking);
        $this->lead = $this->booking->leadTraveller ?? BookingTravellerRepository::make([]);

        if ($this->lead->id === null) {
            $this->lead->booking_id = $booking->id;
            $this->lead->save();
            $booking->lead_traveller_id = $this->lead->id;
            $booking->booking_accommodation_id = $this->selectedHotel;
            $booking->save();
            $this->addTraveller();
        }        
    }

    public function toggleCustomerForm()
    {
        $this->showCustomerForm = !$this->showCustomerForm;
        if (!$this->showCustomerForm) {
            $this->resetErrorBag();
        }
    }

    public function getCanSendQuoteProperty(): bool
    {
        return !empty($this->lead->first_name) && !empty($this->lead->last_name);
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'lead.')) {
            $this->validateOnly($property);
            $this->lead->save();
        }
    }

    public function sendQuote(): void
    {
        $this->validate();
        $this->lead->first_name = trim($this->lead->first_name);
        $this->lead->last_name = trim($this->lead->last_name);
        $this->lead->mobile_number = trim($this->lead->mobile_number ?? '');
        $this->lead->save();

        try {
            if (RateLimiter::tooManyAttempts("send-quote-{$this->booking->id}", 5)) {
                throw ValidationException::withMessages(['email' => 'Too many attempts. Please try again later.']);
            }

            RateLimiter::hit("send-quote-{$this->booking->id}");
            $quote = $this->booking->repository->convertToQuote();
            $sent = $quote->repository->generateSent($this->lead->email_address, $quote->paying?? 1, $quote->travelling?? 0);
            $attachment = new Attachment($quote->repository->getStream($sent), $quote->reference . '.pdf', ['mime' => 'application/pdf',]);
            $status = (new QuoteMail('quote', $quote->consultant))->send($target ?? $sent->recipient, $sent, [$attachment,], "", true);
            $this->quoteSent = true;
            $this->showCustomerForm = false;
            session()->flash('success', 'Quote emailed successfully!');
        } catch (MailDisabledException|MailFailedException $e) {
            session()->flash('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Quote email failed', ['booking_id' => $this->booking->id, 'error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send quote. Please try again later.');
        }
    }

    public function updateCurrency(string $currency)
    {
        $this->selectedCurrency = $currency;
        $this->booking->repository->updateCurrency($currency);
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

    public function convertLeadToCustomer(): ? Customer
    {
        if (empty($this->lead->email_address)) {
            Log::warning("Lead traveller missing email for booking ID {$this->booking->id}");
            return null;
        }

        try {
            $this->lead->first_name = $this->lead->first_name ?? 'Unset';
            $this->lead->last_name = $this->lead->last_name ?? 'Unset';
            $this->lead->save();

            $repository = new BookingTravellerRepository($this->lead);
            $customer = $repository->convertToCustomer();

            if (!$this->lead->customer_id) {
                $this->lead->customer_id = $customer->id;
                $this->lead->save();
            }
            return $customer;
        } catch (\Throwable $e) {
            Log::error("Failed to convert lead to customer", [
                'booking_id' => $this->booking->id,
                'lead_id' => $this->lead->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getRules()
    {
        $rules = [
            'lead.email_address' => 'required|email',
        ];

        if ($this->showCustomerForm) {
            $rules['lead.first_name'] = 'required|string|max:255';
            $rules['lead.last_name'] = 'required|string|max:255';
            $rules['lead.mobile_number'] = 'nullable|string|regex:/^[0-9+\-\s()]*$/|max:20';
        }

        return $rules;
    }

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