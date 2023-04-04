<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Repository\Storage\Customer\Component\BookingComponent;
use Livewire\Component;

class ComponentCard extends Component
{
    public BookingComponent $component;
    public function render()
    {
        return view('livewire.customer.booking.component-card');
    }
}
