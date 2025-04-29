<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Details extends V3BookingComponent
{

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