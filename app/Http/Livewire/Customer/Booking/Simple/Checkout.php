<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Http\Livewire\SendsEvents;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Location\Address;
use Livewire\Component;

class Checkout extends Component
{
    use SendsEvents;

    public Booking|int $booking;
    public BookingTraveller $payer;
    public Address $payerAddress;

    public function mount(Booking|int $booking)
    {
        $this->booking = Booking::getForMount($booking);
        $this->payer = $this->booking->leadTraveller;
    }

    public function toggleLeadPaying(): void
    {
        if ($this->payer->id === $this->booking->lead_traveller_id) {
            $this->payer = new BookingTraveller(['booking_id' => $this->booking->id]);
        } else {
            $this->payer = $this->booking->leadTraveller;
        }
        $this->updateValue('payer.mobile_number', $this->payer->mobile_number);
    }

    public function checkout()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.customer.booking.simple.checkout');
    }

    public function rules()
    {
        return [
            'payer.first_name' => 'required|string',
            'payer.last_name' => 'required|string',
            'payer.email_address' => 'required|email:rfc,dns',
            'payer.mobile_number' => 'required|phone:INTERNATIONAL',
            'payer.date_of_birth' => 'required|date',
        ];
    }
}
