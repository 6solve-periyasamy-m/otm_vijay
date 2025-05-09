<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Location\Address;
use App\Models\Location\Country;
use Carbon\Carbon;

class Details extends V3BookingComponent
{
    public $listeners = ['currencyUpdated' => 'updateCurrency'];
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
    public BookingTraveller|null $lead = null;
    public Address $leadAddress, $payerAddress;
    public bool $leadIsTravelling = true;
    public BookingTraveller $payer;
    public bool $terms = false;

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
        $this->payer = $this->booking->leadTraveller;
        $date = trim($this->payer->date_of_birth);
        $this->payer_date_of_birth_formatted = optional($this->payer->date_of_birth)->format('d-m-Y');
        $this->leadAddress = $this->lead->billingAddress ?? new Address();
        $this->payerAddress = $this->payer->homeAddress ?? new Address();
        $this->countries = Country::orderBy('priority', 'desc')->orderBy('name')->get(['id', 'name'])->toArray(); 
        $this->leadIsTravelling = $this->payer->role !== BookingTravellerRole::NOT_TRAVELLING;
        if ($this->leadIsTravelling) {
            $this->lead = new BookingTraveller();
            $this->leadAddress = $this->lead->homeAddress ?? new Address();
            $this->lead_date_of_birth_formatted = '';
        } else {
            $leadTraveller = BookingTraveller::where('booking_id', $this->booking->id)
                        ->where('role', 1) // role 1 for lead traveller
                        ->first();
            $this->lead = $leadTraveller;
            $this->lead_date_of_birth_formatted = optional($this->lead->date_of_birth)->format('d-m-Y');
            $this->leadAddress = $this->lead->homeAddress ?? new Address();
        }
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
        // if ($this->booking->leadTraveller->role === BookingTravellerRole::NOT_TRAVELLING) { return; }
        // $this->payer = $this->booking->travellers()->where('role', '=', BookingTravellerRole::NOT_TRAVELLING)->first() ??
        //     $this->booking->repository->makeTraveller([
        //         'role' => BookingTravellerRole::NOT_TRAVELLING,
        //     ]);
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.details');
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        if (str_starts_with($key, 'payer')) {
            $this->saveTravellerProfile();
        }
    }

    private function preCheckout(): void
    {
        $this->validate();
        $this->saveLeadTraveller();
    }

    public function saveLeadTraveller(): void
    {
        if (!$this->leadIsTravelling) {
            $this->lead->first_name = trim($this->lead->first_name);
            $this->lead->last_name = trim($this->lead->last_name);
            $this->lead->mobile_number = trim($this->lead->mobile_number ?? '');
            $this->lead->date_of_birth = $this->lead->date_of_birth ? Carbon::parse($this->lead->date_of_birth)->format('Y-m-d') : '';
            $this->lead->email_address = trim($this->lead->email_address);
            $customer = Customer::where('email_address', trim($this->lead->email_address))->first();
            $customerData = [
                'home_address_id' => isset($customer) ? $customer->home_address_id : $this->payer->home_address_id,
                'billing_address_id' => isset($customer) ? $customer->billing_address_id : $this->payer->billing_address_id,
                'email_address' => $this->lead->email_address,
                'first_name' => $this->lead->first_name,
                'last_name' => $this->lead->last_name,
                'date_of_birth' => $this->lead->date_of_birth,
                'mobile_number' => $this->lead->mobile_number
            ];
            if (isset($customer) && isset($customer->email_address)) {
                $customer->update($customerData);
                $customer->save();
            } else {
                $customer = Customer::make($customerData);
                $customer->save();
            }
            $this->lead->customer_id = $customer->id;
            $leadTraveller = BookingTraveller::where('booking_id', $this->booking->id)
                        ->where('role', 1) // role 1 for lead traveller
                        ->first();
            if ($leadTraveller) {
                // Update the lead traveller details
                $leadTraveller->first_name = $this->lead->first_name;
                $leadTraveller->last_name = $this->lead->last_name;
                $leadTraveller->mobile_number = $this->lead->mobile_number;
                $leadTraveller->email_address = $this->lead->email_address;
                $leadTraveller->date_of_birth = $this->lead->date_of_birth;
                //$leadTraveller->country_id  = $this->leadAddress->country_id;
                $leadTraveller->customer_id  = $customer->id;
                $leadTraveller->save();
            }
            $this->lead->leadAddress = $this->payer->homeAddress;
            $this->lead->leadBillingAddress = $this->payer->billingAddress;
            $this->lead->leadAddress->country_id = $this->leadAddress->country_id;
            $this->lead->leadBillingAddress->country_id = $this->leadAddress->country_id;
            $this->lead->leadAddress->save();
            $this->lead->leadBillingAddress->save();
            $this->booking->notes = $this->booking->notes;
            $this->booking->save();
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
                'postcode' => $this->leadAddress->postcode,
            ]);
            $this->payer->homeAddress()->associate($address);
            $this->payer->save();
        }
        if ($this->payer->homeAddress === null) {
            $address = Address::create([
                'name' => $this->payer->first_name . ' ' . $this->payer->last_name . ' Billing Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $this->leadAddress->postcode,
            ]);
            $this->payer->billingAddress()->associate($address);
            $this->payer->save();
        }
        $this->payer->homeAddress->postcode = $this->leadAddress->postcode;
        $this->payer->homeAddress->save();
        $this->payer->billingAddress->postcode = $this->leadAddress->postcode;
        $this->payer->billingAddress->save();
    }

    public function updatedPayerDateOfBirthFormatted($value)
    {
        try {
            $this->payer->date_of_birth = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            $this->payer->save();
        } catch (\Exception $e) {
            $this->addError('payer_date_of_birth_formatted', 'Invalid date format. Use dd-mm-yyyy.');
        }
    }

    public function updatedLeadDateOfBirthFormatted($value)
    {
        try {
            $this->lead->date_of_birth = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->addError('lead_date_of_birth_formatted', 'Invalid date format. Use dd-mm-yyyy.');
        }
    }

    private function saveTravellerProfile()
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

    public function rules()
    {
        $rules = [
            'payer.email_address' => 'required|email',
            'payer.first_name' => 'required|string|max:255',
            'payer.last_name' => 'required|string|max:255',
            'payer.mobile_number' => 'nullable|string|regex:/^[0-9+\-\s()]*$/|max:20',
            'payerAddress.country_id' => 'nullable|exists:countries,id',
        ];

        if (!$this->leadIsTravelling) {
            $rules = array_merge($rules, [
                'lead.email_address' => 'required|email',
                'lead.first_name' => 'required|string|max:255',
                'lead.last_name' => 'required|string|max:255',
                'lead.mobile_number' => 'required|string|regex:/^[0-9+\-\s()]*$/|max:20',
                'leadAddress.country_id' => 'nullable|exists:countries,id',
                'booking.notes' => 'nullable|string|max:1000',
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
        $this->dispatchBrowserEvent('popupStripeCheckout', ['full' => $full,]);
    }
}