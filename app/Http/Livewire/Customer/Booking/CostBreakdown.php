<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\Booking;
use Livewire\Component;

class CostBreakdown extends Component
{
    public Booking $booking;

    protected $listeners = ['travellerAdded' => 'render', 'travellerRemoved' => 'render', 'componentsChanged' => 'render'];

    public function render()
    {
        return view('livewire.customer.booking.cost-breakdown');
    }
}
