<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Country;
use App\Models\Location\Address;
use App\Models\Customer\Organization;
use App\Models\Customer\Customer;
use Carbon\Carbon;


class Details extends V3BookingComponent
{
    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

    protected array $messages = [
        'buyer.email_address.required' => 'Email is required.',
        'buyer.email_address.email' => 'Please enter a valid email address.',
        'buyer.first_name.required' => 'First name is required.',
        'buyer.last_name.required' => 'Last name is required.',
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
        'lead.mobile_number.required' => 'Mobile number is required.',
        'leadAddress.country_id.required' => 'Country is required.',
    ];

    public BookingTraveller|null $buyer = null;
    public Address $buyerAddress, $leadAddress, $delivery, $billing;
    public bool $sameAsLeadTraveller = false;
    public BookingTraveller|null $lead = null;
    public $organization;
    public $countries;

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);

        // Load buyer as lead traveller
        $this->buyer = $this->booking->leadTraveller;
        $this->buyerAddress = $this->buyer->homeAddress ?? new Address();
        $this->buyerBillingAddress = $this->buyer->billingAddress ?? new Address();
        $this->countries = Country::orderBy('priority', 'desc')->orderBy('name')->get(['id', 'name'])->toArray();
        $this->organization = $this->quote->organization_id ? Organization::find($this->quote->organization_id) : new Organization();
        $this->lead = new BookingTraveller();
        $this->leadAddress = $this->lead->homeAddress ?? new Address();
        $this->sameAsLeadTraveller = $this->booking->purchaser_is_lead ? true : false;

        if ($this->sameAsLeadTraveller === false) {
            if ($this->organization->id !== null) {
                $orgName = explode(" ", $this->organization->name);
                $this->buyer->first_name = $orgName[0];
                $this->buyer->last_name = $orgName[1];
                $this->buyer->mobile_number = $this->organization->contact_number;
                $this->buyer->email_address = $this->organization->contact_email;
                $this->buyerAddress = $this->organization->deliveryAddress;

                $leadTraveller = BookingTraveller::where('booking_id', $this->booking->id)
                        ->where('role', 1) // role 1 for lead traveller
                        ->first();
                $this->lead = $leadTraveller;
                $this->leadAddress = $this->lead->homeAddress ?? new Address();
            }
        }
    }

    public function back()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function updateOrganization()
    {
        $this->delivery = $organization->deliveryAddress ?? new Address(['parent' => AddressParent::ORGANIZATION]);
        $this->billing = $organization->billingAddress ?? new Address(['parent' => AddressParent::ORGANIZATION]);
        // Add/updated the organization details
        $this->organization->name = trim($this->buyer?->first_name . ' ' . $this->buyer?->last_name);
        $this->organization->contact_email = $this->buyer?->email_address;
        $this->organization->contact_number = $this->buyer?->mobile_number;
        $this->delivery->name = "{$this->organization->name} - Delivery Address";
        $this->billing->name = "{$this->organization->name} - Billing Address";
        $this->delivery->country_id = $this->buyerAddress->country_id;
        $this->billing->country_id = $this->buyerAddress->country_id;
        $this->delivery->save();
        $this->billing->save();
        $this->organization->delivery_address_id = $this->delivery->id;
        $this->organization->billing_address_id = $this->billing->id;
        $this->organization->save();
        $this->quote->organization_id = $this->organization->id;
        $this->quote->commission = $this->organization?->commission;
        $this->quote->save();
    }

    public function advance()
    {
        $this->validate();
        // Check if the lead traveller fields are filled and "Is purchaser the same person as lead traveller" is selected as "No"
        if (!$this->sameAsLeadTraveller && $this->lead) {
            $this->updateOrganization();
            // To update the lead traveller for not same as purchaser
            $this->lead->first_name = trim($this->lead->first_name);
            $this->lead->last_name = trim($this->lead->last_name);
            $this->lead->mobile_number = trim($this->lead->mobile_number ?? '');
            $this->lead->date_of_birth = $this->lead->date_of_birth ? Carbon::parse($this->lead->date_of_birth)->format('Y-m-d') : '';
            $this->lead->email_address = trim($this->lead->email_address);
            $customer = Customer::where('email_address', trim($this->lead->email_address))->first();
            $customerData = [
                'home_address_id' => isset($customer) ? $customer->home_address_id : $this->buyer->home_address_id,
                'billing_address_id' => isset($customer) ? $customer->billing_address_id : $this->buyer->billing_address_id,
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
                $leadTraveller->country_id  = $this->leadAddress->country_id;
                $leadTraveller->customer_id  = $customer->id;
                $leadTraveller->save();
            }
            $this->lead->leadAddress = $this->buyer->homeAddress;
            $this->lead->leadBillingAddress = $this->buyer->billingAddress;
            $this->lead->leadAddress->country_id = $this->leadAddress->country_id;
            $this->lead->leadBillingAddress->country_id = $this->leadAddress->country_id;
            $this->lead->leadAddress->save();
            $this->lead->leadBillingAddress->save();
           $this->booking->purchaser_is_lead = '';
            $this->booking->save();

        } else {
            $this->booking->purchaser_is_lead = 1;
            $this->booking->save();
            $this->quote->organization_id = null;
            $this->quote->save();
            $this->saveTravellerProfile();
        }
        // Proceed with other logic
        return redirect()->route('booking.v3.details', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    private function saveTravellerProfile()
    {
        // Save buyer's profile (if organization_id is not set)
        $this->buyer->date_of_birth = $this->buyer->date_of_birth ? Carbon::parse($this->buyer->date_of_birth)->format('Y-m-d') : '';
        $this->buyer->country_id = $this->buyerAddress->country_id;
        $this->buyer->save();
        $this->booking->lead_traveller_id = $this->buyer->id;
        $this->booking->save();

        if ($this->buyer->homeAddress === null) {
            $address = Address::create([
                'name' => $this->buyer->first_name . ' ' . $this->buyer->last_name . ' Home Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $this->buyerAddress->postcode,
            ]);
            $this->buyer->homeAddress()->associate($address);
            $this->buyer->save();
        }

        if ($this->buyer->homeAddress === null) {
            $address = Address::create([
                'name' => $this->buyer->first_name . ' ' . $this->buyer->last_name . ' Billing Address',
                'parent' => AddressParent::CUSTOMER,
                'postcode' => $this->buyerAddress->postcode,
            ]);
            $this->buyer->billingAddress()->associate($address);
            $this->buyer->save();
        }

        $this->buyer->homeAddress->country_id = $this->buyerAddress->country_id;
        $this->buyer->homeAddress->save();
        $this->buyer->billingAddress->country_id = $this->buyerAddress->country_id;
        $this->buyer->billingAddress->save();
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.details');
    }

    // public function updated($key, $value): void
    // {
    //     if (str_starts_with($key, 'buyer')) {
    //         $this->validateOnly($key);
    //     }
    // }

    public function rules()
    {
        $rules = [
            'buyer.email_address' => 'required|email',
            'buyer.first_name' => 'required|string|max:255',
            'buyer.last_name' => 'required|string|max:255',
            'buyer.mobile_number' => 'required|string|regex:/^[0-9+\-\s()]*$/|max:20',
            'buyerAddress.country_id' => 'required|exists:countries,id',
            'buyer.date_of_birth' => 'nullable|date:d-m-Y',
        ];

        if (!$this->sameAsLeadTraveller) {
            $rules = array_merge($rules, [
                'lead.email_address' => 'required|email',
                'lead.first_name' => 'required|string|max:255',
                'lead.last_name' => 'required|string|max:255',
                'lead.mobile_number' => 'required|string|regex:/^[0-9+\-\s()]*$/|max:20',
                'leadAddress.country_id' => 'required|exists:countries,id',
                'lead.date_of_birth' => 'nullable|date:d-m-Y',
            ]);
        }

        return $rules;
    }
}
    //         //$this->saveTravellerProfile();
    //         // // If organization_id is set, save to the organization, otherwise, save buyer's profile
    //         // if ($this->quote->organization_id) {
    //         //     $this->updateOrganization();
    //         // } else {
    //         //     $this->saveTravellerProfile();
    //         // }