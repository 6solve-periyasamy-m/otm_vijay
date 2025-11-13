<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Inclusion extends V3BookingComponent
{
    public $listeners = ['currencyUpdated' => 'updateCurrency', 'advanceWithRecaptcha' => 'advanceWithRecaptcha'];
    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
    }

    public function advanceWithRecaptcha(string $token)
    {
        return $this->advance();
    }

    public function addGuest(): void
    {
       //TODO - find the guest and update the guest count
    }

    public function removeGuest(): void
    {
       //TODO - find the guest and removed the guest count
    }

    public function back()
    {
        return redirect()->route('booking.v3.tickets', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        $this->booking->updateBookingProgressNotification('Additional Inclusions');
        $this->booking->last_page = 'Additional Inclusions';
        $this->booking->save();
        return redirect()->route('booking.v3.details', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.inclusion');
    }
}
