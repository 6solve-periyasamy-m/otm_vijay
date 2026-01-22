<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Gateways\StripeGateway;
use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Country;
use Carbon\Carbon;
use Log;

class Details extends V3BookingComponent
{
    public $listeners = ['currencyUpdated' => 'updateCurrency', 'advanceWithRecaptcha' => 'advanceWithRecaptcha'];
    protected array $messages = [
        'payer.email_address.required' => 'Email is required.',
        'payer.email_address.email' => 'Please enter a valid email address.',
        'payer.first_name.required' => 'First name is required.',
        'payer.last_name.required' => 'Last name is required.',
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
        'lead.mobile_number.required' => 'Mobile number name is required.',
    ];
    // Payer Details
    public BookingTraveller $payer;
    public Address $payerAddress;
    public $payer_date_of_birth_formatted;

    // Lead Details
    public BookingTraveller|null $lead = null;
    public Address $leadAddress;
    public string $lead_date_of_birth_formatted;

    // Other
    public bool $leadIsTravelling = true;
    public bool $terms = false;

    /** @var array<int, string> */
    public array $countries = [];

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);

        $this->payer = $this->booking->leadTraveller;
        $this->payer_date_of_birth_formatted = optional($this->payer->date_of_birth)->format('d-m-Y');

        $this->leadAddress = $this->lead->billingAddress ?? new Address();
        $this->payerAddress = $this->payer->homeAddress ?? new Address();

        $this->countries = Country::orderBy('priority', 'desc')->orderBy('name')->get(['id', 'name'])->toArray();

        $this->leadIsTravelling = $this->payer->role !== BookingTravellerRole::NOT_TRAVELLING;

        if ($this->leadIsTravelling) {
            $this->lead = new BookingTraveller();
            $this->lead_date_of_birth_formatted = '';
        } else {
            $leadTraveller = $this->booking->travellers()->where('role', BookingTravellerRole::NORMAL)->first();
            $this->lead_date_of_birth_formatted = optional($leadTraveller?->date_of_birth)->format('d-m-Y');
            $this->lead = $leadTraveller;
        }

        $this->leadAddress = $this->lead->homeAddress ?? new Address();
    }

    public function advanceWithRecaptcha(string $token): void
    {
        $this->advance();
    }

    public function setLeadPaying(bool $areDifferent = false): void
    {
        if ($areDifferent) {
            $this->leadIsTravelling = false;
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
            $this->leadIsTravelling = true;
            if ($this->lead->id === $this->payer->id) { return; }
            $this->lead->repository->moveComponents($this->payer);
            $this->lead->delete();
            $this->payer->role = BookingTravellerRole::NORMAL;
            $this->lead = $this->payer;
        }
        $this->payer->save();
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

    public function render()
    {
        return view('livewire.customer.booking.v3.details');
    }

    private function saveAll(): void
    {
        $this->booking->save();
        $this->payer->date_of_birth = Carbon::parse($this->payer->date_of_birth)->format('Y-m-d');
        $this->payer = $this->saveTraveller($this->payer, $this->payer_date_of_birth_formatted, $this->payerAddress->country_id, $this->payerAddress->postcode);
        if ($this->payer->id !== $this->lead->id) {
            $this->lead = $this->saveTraveller($this->lead, $this->lead_date_of_birth_formatted, $this->leadAddress->country_id, $this->leadAddress->postcode);
        }
    }

    private function saveTraveller(BookingTraveller $traveller, string|null $dob, string|null $country = null, string|null $postcode = null): BookingTraveller
    {
        $traveller->booking_id = $this->booking->id;
        $traveller->date_of_birth = Carbon::parse($dob)->format('Y-m-d');
        if ($traveller->homeAddress === null) {
            $address = Address::create([
                'name' => $traveller->first_name . ' ' . $traveller->last_name . ' Home Address',
                'parent' => AddressParent::CUSTOMER,
                'country_id' => $country,
                'postcode' => $postcode,
            ]);
            $traveller->homeAddress()->associate($address);
            $traveller->save();
        }
        if ($traveller->homeAddress === null) {
            $address = Address::create([
                'name' => $traveller->first_name . ' ' . $traveller->last_name . ' Billing Address',
                'parent' => AddressParent::CUSTOMER,
                'country_id' => $country,
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
        if (!$this->terms) { return $this->addError('common', 'You must accept terms and conditions.'); }
        $this->validate();
        $this->saveAll();
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
            Log::error($e);
            $this->addError('common', 'Something went wrong with our payment processing. Please try again later.');
            return null;
        }
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        if (str_starts_with($key, 'payer')) {
            $this->saveTravellerProfile();
        }
    }

    public function updatedPayerDateOfBirthFormatted($value): void
    {
        try {
            $this->payer->date_of_birth = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            $this->payer->save();
        } catch (\Exception $e) {
            $this->addError('payer_date_of_birth_formatted', 'Invalid date format. Use dd-mm-yyyy.');
        }
    }

    public function updatedLeadDateOfBirthFormatted($value): void
    {
        try {
            $this->lead->date_of_birth = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->addError('lead_date_of_birth_formatted', 'Invalid date format. Use dd-mm-yyyy.');
        }
    }

    private function saveTravellerProfile(): void
    {
        if (!empty($this->payer->date_of_birth)) {
            try {
                $this->payer->date_of_birth = \Carbon\Carbon::parse($this->payer->date_of_birth)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->addError('payer.date_of_birth', 'Invalid date format.');
                return;
            }
        }
        $this->payer->save();
        $this->booking->lead_traveller_id = $this->payer->id;
        $this->booking->save();
        $this->payer->homeAddress->country_id = $this->payerAddress->country_id;
        $this->payer->homeAddress->save();
        $this->payer->billingAddress->country_id = $this->payerAddress->country_id;
        $this->payer->billingAddress->save();        
    }

    public function rules(): array
    {
        $rules = [
            'payer.email_address' => 'required|email',
            'payer.first_name' => 'required|string|max:255',
            'payer.last_name' => 'required|string|max:255',
            'payer.mobile_number' => 'nullable|string|regex:/^[0-9+\-\s()]*$/|max:20',
            'payerAddress.country_id' => 'nullable|exists:countries,id',
            'booking.notes' => 'nullable|string|max:1000',
        ];

        if (!$this->leadIsTravelling) {
            $rules = array_merge($rules, [
                'lead.email_address' => 'required|email',
                'lead.first_name' => 'required|string|max:255',
                'lead.last_name' => 'required|string|max:255',
                'lead.mobile_number' => 'required|string|regex:/^[0-9+\-\s()]*$/|max:20',
                'leadAddress.country_id' => 'nullable|exists:countries,id',
            ]);
        }

        return $rules;
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

    public function back()
    {
        if ($this->hasInclusions()) {
            return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
        }

        return redirect()->route('booking.v3.tickets', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance(): void
    {
        $this->booking->updateBookingProgressNotification('Details');
        $this->booking->last_page = 'Details';
        $this->booking->save();
        $this->checkout();
    }
}
