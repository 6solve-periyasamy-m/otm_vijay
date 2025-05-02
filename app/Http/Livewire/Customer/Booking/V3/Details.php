<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Country;
use App\Models\Location\Address;
use Carbon\Carbon;

class Details extends V3BookingComponent
{
    protected $listeners = ['currencyUpdated' => 'updateCurrency'];
    protected array $messages = [
        'lead.email_address.required' => 'Email is required.',
        'lead.email_address.email' => 'Please enter a valid email address.',
        'lead.first_name.required' => 'First name is required.',
        'lead.last_name.required' => 'Last name is required.',
    ];
    public BookingTraveller|null $lead = null;
    public Address $leadAddress;

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
        $this->lead = $this->booking->leadTraveller;
        $this->leadAddress = $this->lead->billingAddress ?? new Address();
        $this->countries = Country::orderBy('priority', 'desc')->orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function back()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        return; // Final page at the moment
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