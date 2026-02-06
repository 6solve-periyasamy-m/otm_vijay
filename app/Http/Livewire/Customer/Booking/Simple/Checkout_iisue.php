<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Http\Controllers\Customer\BookingV3Controller;
use App\Http\Gateways\StripeGateway;
use App\Http\Livewire\SendsEvents;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Currency;
use Carbon\Carbon;
use Exception;
use Gateway;
use Livewire\Component;
use Log;
use Settings;

class Checkout extends Component
{
    use SendsEvents;

    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

    public Booking|int $booking;
    public BookingTraveller $payer;
    public BookingTraveller $lead;
    public Address $payerAddress;

    public bool $payFull = true;
    public bool $terms = false;
    public string|null $selectedCurrency = null;
    public $is_different_traveller = false;

    public function mount(Booking|int $booking)
    {
        $this->booking = Booking::getForMount($booking);
        $this->payer = $this->booking->leadTraveller;
        $this->lead = $this->booking->leadTraveller;
        $this->payerAddress = $this->payer->billingAddress ?? new Address();
        $this->updateSurchargeAmount();
        $this->selectedCurrency = $this->getCurrency()?->code;
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        if ($key === 'is_different_traveller') {
            // Value is a string at this point, so needs converting
            $this->setLeadPaying(filter_var($value, FILTER_VALIDATE_BOOLEAN));
        }
        $this->saveAll();
    }

    public function setLeadPaying(bool $areDifferent = false): void
    {
        if ($areDifferent) {
            if ($this->lead->id !== $this->payer->id) { return; }
            $this->payer->repository->moveComponents($this->lead);
            $this->payer->role = BookingTravellerRole::NOT_TRAVELLING;
            $this->lead = BookingTraveller::make([
                'role' => BookingTravellerRole::NORMAL,
                'booking_id' => $this->booking->id,
            ]);
            $this->lead->save();
            $this->payer->repository->moveComponents($this->lead);
        } else {
            if ($this->lead->id === $this->payer->id) { return; }
            $this->lead->repository->moveComponents($this->payer);
            $this->lead->delete();
            $this->payer->role = BookingTravellerRole::NORMAL;
            $this->lead = $this->payer;
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
        $this->updateSurchargeAmount();
    }

    private function saveAll(): void
    {
        $this->booking->save();
        $this->payer->date_of_birth = Carbon::parse($this->payer->date_of_birth)->format('Y-m-d');
        $this->payer = $this->saveTraveller($this->payer, $this->payerAddress->postcode);
        if ($this->payer->id !== $this->lead->id) {
            $this->lead = $this->saveTraveller($this->lead);
        }
    }
    
    private function saveTraveller(BookingTraveller $traveller, string|null $postcode = null): BookingTraveller
    {
        $traveller->booking_id = $this->booking->id;
        if ($traveller->homeAddress === null) {
            $address = Address::create([
                'name' => $traveller->first_name . ' ' . $traveller->last_name . ' Home Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $postcode,
            ]);
            $traveller->homeAddress()->associate($address);
            $traveller->save();
        }
        if ($traveller->homeAddress === null) {
            $address = Address::create([
                'name' => $traveller->first_name . ' ' . $traveller->last_name . ' Billing Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $postcode,
            ]);
            $traveller->billingAddress()->associate($address);
            $traveller->save();
        }
        $traveller->save();
        return $traveller;
    }

    public function checkout()
    {
        $this->validate();
        $this->saveAll();
        $this->updateSurchargeAmount();

        if (!$this->terms) {
            return $this->addError('common', 'You must accept terms and conditions.');
        }

        if (!$this->booking->repository->validateStock()) {
            $bEmail = $this->booking->tour->brand->email;
            return $this->addError('common', "Some components in this package are out-of-stock. Please contact us at {$bEmail} for alternative options.");
        }

        foreach ($this->booking->travellers as $traveller) {
            $traveller->repository->validateIncluded();
        }
        $amount = ($this->payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount()) * $this->getFXRate();
        try {
            if (Gateway::getPaymentGateway('stripe') !== null) {
                $this->popupStripe($this->payFull);
                return null;
            }

            $keys = $this->booking->repository->getAirwallexKeys($amount);
            if ($keys !== null && array_key_exists('id', $keys) && array_key_exists('secret', $keys)) {
                $this->popupAirwallex($keys['id'], $keys['secret']);
                return null;
            }

            return redirect($this->booking->repository->getCheckoutLink($amount));
        } catch (Exception $e) {
            Log::error($e);
            $this->addError('common', 'Something went wrong with our payment processing. Please try again later.');
            return null;
        }
    }

    public function render()
    {
        $this->booking = Booking::find($this->booking->id);
        return view('livewire.customer.booking.simple.checkout');
    }

    public function rules()
    {
        return [
            'payer.first_name' => 'required|string',
            'payer.last_name' => 'required|string',
            'payer.email_address' => 'required|email:rfc,dns',
            'payer.mobile_number' => 'required|phone:INTERNATIONAL',
            'lead.first_name' => 'required_with:is_different_traveller|string',
            'lead.last_name' => 'required_with:is_different_traveller|string',
            'lead.email_address' => 'required_with:is_different_traveller|email:rfc,dns',
            'lead.mobile_number' => 'required_with:is_different_traveller|phone:INTERNATIONAL',
            'payer.date_of_birth' => 'nullable|date:d-m-Y',
            'payerAddress.postcode' => 'nullable|string',
            'booking.notes' => 'nullable|string',
        ];
    }

    private function popupAirwallex(string $id, string $secret): void
    {
        $this->dispatchBrowserEvent('popupCheckout', ['key' => $id, 'secret' => $secret]);
    }

    private function popupStripe(bool $full = false): void
    {
        $currencyKey = config('app.gateways.stripe.currencies.' . $this->getCurrency()->code, []);
        $this->dispatchBrowserEvent('popupStripeCheckout', [
            'full' => $full,
            'currency' => $this->getCurrency()->code,
            'publishable' => $currencyKey['client'] ?? config('app.gateways.stripe.publishable'),
        ]);
    }

    public function getCurrency(): Currency
    {
        return $this->booking->repository->getCurrency();
    }

    public function getFXRate(): float
    {
        return $this->booking->repository->getFXRate();
    }

    public function formatCurrency(int|float|null $value, bool $round = true, int $decimalPrecision = 0): string
    {
        return f_currency_booking($value, $this->getCurrency(), $this->getFXRate(), $decimalPrecision);
    }

    public function updateCurrency(string $currency): void
    {
        if (in_array(strtoupper($currency), BookingV3Controller::ALLOWED_CURRENCIES)) {
            $this->booking->currency_id = Currency::where('code', $currency)->first()?->id ?? Settings::currency()?->id;
            $this->booking->repository->updateCurrency($currency);
            $this->render();
        }
    }

    private function updateSurchargeAmount(): void
    {
        $amount = $this->getSurchargeAmount();
        $this->dispatchBrowserEvent('surcharge-update', ['amount' => $amount, 'text' => fr_currency($amount, $this->booking->currency), 'percent' => $this->getSurchargePercentage(),]);
    }

    public function getSurchargeAmount(): float|null
    {
        $amount = $this->payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount();
        return StripeGateway::getAmountForSurcharge($this->getCurrency(), $amount * 100) / 100;
    }

    public function getSurchargePercentage(): float|null
    {
        return StripeGateway::getStripeSurcharge($this->getCurrency());
    }
}
