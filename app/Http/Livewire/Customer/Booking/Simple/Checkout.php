<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Exceptions\RemoteGatewayError;
use App\Exceptions\UnauthorizedGatewayException;
use App\Http\Livewire\SendsEvents;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Repository\Model\Booking\BookingTravellerRepository;
use Livewire\Component;

class Checkout extends Component
{
    use SendsEvents;

    public Booking|int $booking;
    public BookingTraveller $payer;
    public Address $payerAddress;

    public bool $payFull = true;
    public bool $terms = false;

    public function mount(Booking|int $booking)
    {
        $this->booking = Booking::getForMount($booking);
        $this->payer = $this->booking->leadTraveller;
    }

    public function toggleLeadPaying(): void
    {
        if ($this->payer->id === $this->booking->lead_traveller_id) {
            $this->payer =
                $this->booking->travellers()->where('role', '=', BookingTravellerRole::NOT_TRAVELLING)->first()
                ?? $this->booking->repository->makeTraveller([
                    'role' => BookingTravellerRole::NOT_TRAVELLING,
                ]);
        } else {
            $this->payer = $this->booking->leadTraveller;
        }
        $this->updateValue('payer.mobile_number', $this->payer->mobile_number);
    }

    public function setPayFull(bool $payFull): void
    {
        $this->payFull = $payFull;
    }

    private function preCheckout(): void
    {
        $this->validate();
        $this->payer->save();
        $this->booking->lead_traveller_id = $this->payer->id;
        $this->payer->homeAddress->save();
        $this->payer->billingAddress->save();
    }

    public function checkout()
    {
        if (!$this->terms) { return $this->addError('common', 'You must accept terms and conditions.'); }
        try {
            return redirect($this->booking->repository->getCheckoutLink($this->payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount()));
        } catch (\Exception $e) {
            return null;
        }
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
