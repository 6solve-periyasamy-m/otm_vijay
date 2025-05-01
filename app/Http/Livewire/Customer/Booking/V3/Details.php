<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;

class Details extends V3BookingComponent
{
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
        parent::mount($tour, $booking, $quote);
    }

    public function back()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        return; // Final page at the moment
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.details');
    }
}