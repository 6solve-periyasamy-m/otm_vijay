<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Http\Controllers\Customer\BookingV3Controller;
use App\Http\Livewire\SendsEvents;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Currency;
use Carbon\Carbon;
use Livewire\Component;
use Settings;

class Checkout extends Component
{
    use SendsEvents;

    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

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
        return now()->gt($this->booking->tour?->final_payment);
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
        $this->payer->date_of_birth = Carbon::parse($this->payer->date_of_birth)->format('Y-m-d');
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
        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->validateIncluded();
        }
    }

    public function checkout()
    {
        if (!$this->booking->repository->validateStock()) {
            $bEmail = $this->booking->tour->brand->email;
            return $this->addError('common', "Some components in this package are out-of-stock. Please contact us at {$bEmail} for alternative options.");
        }
        if (!$this->terms) { return $this->addError('common', 'You must accept terms and conditions.'); }
        $this->preCheckout();
        $amount = $this->payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount();
        try {
            $keys = $this->booking->repository->getAirwallexKeys($amount);
            if (\Gateway::getPaymentGateway('stripe') !== null) {
                $this->popupStripe($this->payFull);
                return null;
            } else if ($keys !== null && array_key_exists('id', $keys) && array_key_exists('secret', $keys)) {
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

    public $is_different_traveller = false;

    public function rules()
    {
        return [
            'payer.first_name' => 'required|string',
            'payer.last_name' => 'required|string',
            'payer.email_address' => 'required|email:rfc,dns',
            'payer.mobile_number' => 'required|phone:INTERNATIONAL',
            'payer.date_of_birth' => 'nullable|date:d-m-Y',
            'payerAddress.postcode' => 'nullable|string',
            'booking.notes' => 'nullable|string',
        ];
        // $rules = [
        //     'payer.first_name' => 'required|string',
        //     'payer.last_name' => 'required|string',
        //     'payer.email_address' => 'required|email:rfc,dns',
        //     'payer.mobile_number' => 'required|phone:INTERNATIONAL',
        //     'payer.date_of_birth' => 'nullable|date:d-m-Y',
        //     'payerAddress.postcode' => 'required|string',
        //     'booking.notes' => 'nullable|string',
        // ];
        // if ($this->is_different_traveller) {
        //     $rules['payer.first_name1'] = 'required|string';
        //     $rules['payer.last_name1'] = 'required|string';
        //     $rules['payer.email_address1'] = 'required|email:rfc,dns';
        //     $rules['payer.mobile_number1'] = 'required|phone:INTERNATIONAL';
        // }
        // return $rules;
    }

    private function popupAirwallex(string $id, string $secret): void
    {
        $this->dispatchBrowserEvent('popupCheckout', ['key' => $id, 'secret' => $secret]);
    }

    private function popupStripe(bool $full = false): void
    {
        $this->dispatchBrowserEvent('popupStripeCheckout', ['full' => $full,]);
    }

    public function getCurrency()
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate(): float
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency());
    }

    public function formatCurrency(int|float|null $value, bool $round = true): string
    {
        $value = $value ?? 0.0;
        $value *= $this->getFXRate();
        if ($round && flag('booking.round_to_five')) {
            $value = round_to_five($value);
        }
        return f_currency_booking($value, $this->getCurrency(), true) . " " . $this->getCurrency()->code;
    }

    public function updateCurrency(string $currency): void
    {
        if (in_array(strtoupper($currency), BookingV3Controller::ALLOWED_CURRENCIES)) {
            $this->booking->currency_id = Currency::where('code', $currency)->first()?->id ?? Settings::currency()?->id;
            $this->booking->repository->updateCurrency($currency);
            $this->render();
        }
    }
}
