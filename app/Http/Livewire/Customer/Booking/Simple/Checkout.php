<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Http\Livewire\SendsEvents;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
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
        $this->payerAddress = $this->payer->billingAddress ?? new Address();
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

    public function mustPayAll(): bool
    {
        return now()->gt($this->booking->tour->final_payment);
    }

    public function setPayFull(bool $payFull): void
    {
        if ($this->mustPayAll()) {
            $this->payFull = true;
        } else {
            $this->payFull = $payFull;
        }
    }

    private function saveAll()
    {
        $this->booking->save();
        $this->payer->save();
        $this->booking->lead_traveller_id = $this->payer->id;
        if ($this->payer->homeAddress === null) {
            $address = Address::create([
                'name' => $this->payer->first_name . ' ' . $this->payer->last_name . ' Home Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $this->payerAddress->postcode,
            ]);
            $this->payer->homeAddress()->associate($address);
            $this->payer->save();
        }
        if ($this->payer->homeAddress === null) {
            $address = Address::create([
                'name' => $this->payer->first_name . ' ' . $this->payer->last_name . ' Billing Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $this->payerAddress->postcode,
            ]);
            $this->payer->billingAddress()->associate($address);
            $this->payer->save();
        }
        $this->payer->homeAddress->postcode = $this->payerAddress->postcode;
        $this->payer->homeAddress->save();
        $this->payer->billingAddress->postcode = $this->payerAddress->postcode;
        $this->payer->billingAddress->save();
    }

    private function preCheckout(): void
    {
        $this->validate();
        $this->saveAll();
    }

    public function checkout()
    {
        if (!$this->terms) { return $this->addError('common', 'You must accept terms and conditions.'); }
        $this->preCheckout();
        $amount = $this->payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount();
        try {
            $keys = $this->booking->repository->getAirwallexKeys($amount);
            if ($keys !== null && array_key_exists('id', $keys) && array_key_exists('secret', $keys)) {
                $this->popupAirwallex($keys['id'], $keys['secret']);
                return null;
            } else {
                return redirect($this->booking->repository->getCheckoutLink($amount));
            }
        } catch (\Exception $e) {
            \Log::error($e);
            $this->addError('common', 'Something went wrong with our payment processing. Please try again later.');
            return null;
        }
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        $this->saveAll();
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
            'payer.date_of_birth' => 'nullable|date:Y-m-d',
            'payerAddress.postcode' => 'required|string',
            'booking.notes' => 'nullable|string',
        ];
    }

    private function popupAirwallex(string $id, string $secret): void
    {
        $this->dispatchBrowserEvent('popupCheckout', ['key' => $id, 'secret' => $secret]);
    }
}
