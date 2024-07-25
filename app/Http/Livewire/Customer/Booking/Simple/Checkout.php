<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Tour\Tour;
use Livewire\Component;

class Checkout extends Component
{
    public Booking|int $booking;
    public BookingTraveller $payer;

    public function mount(Booking|int $booking)
    {
        $this->booking = Booking::getForMount($booking);
        $this->payer = $this->booking->leadTraveller;
        //dd($this->payer);
    }

    public function toggleLeadPaying(): void
    {
        if ($this->payer->id === $this->booking->lead_traveller_id) {
            $this->payer = new BookingTraveller(['booking_id' => $this->booking->id]);
        } else {
            $this->payer = $this->booking->leadTraveller;
        }
    }

    public function render()
    {
        return view('livewire.customer.booking.simple.checkout');
    }
}
