<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Country;
use Carbon\Carbon;

class Details extends V3BookingComponent
{
    protected array $messages = [
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
    ];
    public BookingTraveller|null $lead = null;
    public Address $leadAddress;
    public bool $leadIsTravelling = true;
    public BookingTraveller $payer;

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
        $this->lead = $this->booking->leadTraveller;
        $this->leadAddress = $this->lead->billingAddress ?? new Address();
        $this->countries = Country::orderBy('priority', 'desc')->orderBy('name')->get(['id', 'name'])->toArray();
        $this->leadIsTravelling = $this->lead->role !== BookingTravellerRole::NOT_TRAVELLING;
    }

    public function back()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        $this->checkout();
    }

    public function leadIsTravelling()
    {
        $this->leadIsTravelling = true;
        if ($this->booking->leadTraveller->role === BookingTravellerRole::NORMAL) { return; }
        $this->payer = $this->booking->leadTraveller;
    }

    public function leadIsNotTravelling()
    {
        $this->leadIsTravelling = false;
        if ($this->booking->leadTraveller->role === BookingTravellerRole::NOT_TRAVELLING) { return; }
        $this->payer = $this->booking->travellers()->where('role', '=', BookingTravellerRole::NOT_TRAVELLING)->first() ??
            $this->booking->repository->makeTraveller([
                'role' => BookingTravellerRole::NOT_TRAVELLING,
            ]);
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.details');
    }

    public function updated($key, $value): void
    {
        //dd($key, $value);
        $this->validateOnly($key);
        $this->saveTravellerProfile();
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

    private function saveTravellerProfile()
    {

        $this->lead->date_of_birth = Carbon::parse($this->lead->date_of_birth)->format('Y-m-d');
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        //dd($this->lead, $this->leadAddress);
        // if ($this->lead->homeAddress === null) {
        //     $address = Address::create([
        //         'name' => $this->lead->first_name . ' ' . $this->lead->last_name . ' Home Address',
        //         'parent' => AddressParent::CUSTOMER,
        //         'postcode' => $this->leadAddress->postcode,
        //     ]);
        //     $this->lead->homeAddress()->associate($address);
        //     $this->lead->save();
        // }
        // if ($this->lead->homeAddress === null) {
        //     $address = Address::create([
        //         'name' => $this->lead->first_name . ' ' . $this->lead->last_name . ' Billing Address',
        //         'parent' => AddressParent::CUSTOMER,
        //         'postcode' => $this->leadAddress->postcode,
        //     ]);
        //     $this->lead->billingAddress()->associate($address);
        //     $this->lead->save();
        // }
        $this->lead->homeAddress->country_id = $this->leadAddress->country_id;
        $this->lead->homeAddress->save();
        $this->lead->billingAddress->country_id = $this->leadAddress->country_id;
        $this->lead->billingAddress->save();
    }
    public function rules()
    {
        return [
            // 'traveller.email_address' => 'required|email',
            // 'traveller.first_name' => 'required|string|max:255',
            // 'traveller.last_name' => 'required|string|max:255',
            // 'traveller.mobile_number' => 'required|string|regex:/^[0-9+\-\s()]*$/|max:20',
            // 'travellerAddress.country_id' => 'required|exists:countries,id',
            // 'traveller.date_of_birth' => 'nullable|date:d-m-Y',
            'lead.email_address' => 'required|email',
            'lead.first_name' => 'required|string|max:255',
            'lead.last_name' => 'required|string|max:255',
            'lead.mobile_number' => 'nullable|string|regex:/^[0-9+\-\s()]*$/|max:20',
            'leadAddress.country_id' => 'required|exists:countries,id',
            'lead.date_of_birth' => 'nullable|date:d-m-Y',
        ];
    }
}