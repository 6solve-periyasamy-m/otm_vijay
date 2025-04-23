<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use Illuminate\Support\Str;

class Hotel extends V3BookingComponent
{
    public array $roomTypeOrder = ['single', 'double', 'twin', 'triple'];

    public function mount($tour = null, $booking = null)
    {        
        parent::mount($tour, $booking);
        $this->validateRoomCount();
    }

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
