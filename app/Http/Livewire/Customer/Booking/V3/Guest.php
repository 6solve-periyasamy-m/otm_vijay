<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Guest extends V3BookingComponent
{
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
        return redirect()->route('booking.v3.hotel', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }
}
