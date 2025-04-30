<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Models\Helper\Enum\BookingTravellerRole;
use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Customer\Customer;
use App\Repository\Model\Booking\BookingTravellerRepository;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Quote\Quote;
use App\Models\Booking\BookingTraveller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Guest extends V3BookingComponent
{
    private const MAX_TRAVELLERS = 5;
    protected $listeners = ['currencyUpdated' => 'updateCurrency'];  
    protected array $messages = [
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
    ];
    public BookingTraveller|null $lead = null;

    public function mount($tour = null, $booking = null, $quote = null)
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
            $this->booking->quote_id = $quote->id;
            $this->booking->save();
        } catch (\Throwable $e) {
            Log::error('Quote email failed', ['booking_id' => $this->booking->id, 'error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send quote. Please try again later.');
        }
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
        if ($this->booking->quote_id === null) {
            $this->sendQuote();
        } else {
            $quote = Quote::find($this->booking->quote_id);
            $travellers = $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NORMAL)->count();
            $quote->paying = $travellers;
            $quote->save();
        }
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
        $this->renew();
        $this->render();
    }

    public function removeTraveller(): void
    {
        $this->booking->repository->removeUnknownTraveller();
        $this->renew();
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

    public function rules()
    {
        return [
            'lead.email_address' => 'required|email',
            'lead.first_name' => 'required|string|max:255',
            'lead.last_name' => 'required|string|max:255',
            'lead.mobile_number' => 'nullable|string|regex:/^[0-9+\-\s()]*$/|max:20',
        ];
    }
}
