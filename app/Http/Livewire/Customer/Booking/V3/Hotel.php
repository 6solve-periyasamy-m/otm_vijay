<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Hotel extends V3BookingComponent
{
    public function render()
    {
        return view('livewire.customer.booking.v3.hotel');
    }

    public function back()
    {
        return redirect()->route('booking.v3.guest', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance(): void
    {
        return; // Final page right now
    }
}
