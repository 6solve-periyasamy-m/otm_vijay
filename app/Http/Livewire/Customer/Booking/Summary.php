<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use Livewire\Component;

class Summary extends Component
{
    public Booking $booking;
    public BookingTraveller $active;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->active = $booking->leadTraveller;
    }

    public function changeActive($traveller)
    {
        $selected = $this->booking->travellers()->where('id', '=', $traveller)->first();
        if ($selected !== null) {
            $this->active = $selected;
        }
        $this->render();
    }

    public function render()
    {
        return view('livewire.customer.booking.summary');
    }
}
