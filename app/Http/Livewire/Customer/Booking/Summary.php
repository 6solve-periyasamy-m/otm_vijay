<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use Livewire\Component;

class Summary extends Component
{
    public Booking $booking;
    public BookingTraveller $active;
    public bool $accepted;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->active = $booking->leadTraveller;
        $this->accepted = false;
    }

    public function accept()
    {
        $this->accepted = true;
        $this->render();
    }

    public function render()
    {
        return view('livewire.customer.booking.summary');
    }
}
