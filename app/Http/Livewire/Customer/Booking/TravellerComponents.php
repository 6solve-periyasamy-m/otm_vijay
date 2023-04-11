<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\BookingTraveller;
use Livewire\Component;

class TravellerComponents extends Component
{
    public BookingTraveller $traveller;

    protected $listeners = ['componentsChanged',];

    public function componentsChanged(int $travellerId) {
        if ($this->traveller->id === $travellerId) {
            \Log::info('Matches');
            $this->render();
        }
    }

    public function render()
    {
        return view('livewire.customer.booking.traveller-components');
    }
}
