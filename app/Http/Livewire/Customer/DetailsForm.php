<?php

namespace App\Http\Livewire\Customer;

use App\Events\Customer\CustomerEditedEvent;
use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerMerchandise;
use App\Models\Customer\LoyaltyNumber;
use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Customer\AirlineFrequentFlyers;
use App\Models\Helper\Enum\NotificationType;
use Hash;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class DetailsForm extends Component
{
    use LivewireForm, WithFileUploads;

    public Customer $customer;
    public Address $home;
    public Address $billing;
    public string $password = "";
    public $profilePicture;
    public $homeIsBilling = false;
    
    public array $loyalty = [];
    public array $loyaltyToDelete = [];
    public array $merchandise = [];
    public array $merchandiseToDelete = [];
    
    public $airlineFrequentFlyers;
    public $airlineFrequentFlyersId;
    public $membershipNumber;

    protected $messages = [
        'loyalty.*.type.required_with' => 'All fields are required',
        'loyalty.*.name.required_with' => 'All fields are required',
        'loyalty.*.notes.required_with' => 'All fields are required',
        'merchandise.*.category.required_with' => 'All fields are required',
        'merchandise.*.size.required_with' => 'All fields are required',
        'merchandise.*.other_details.required_with' => 'All fields are required',
    ];

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->home = Address::getForMount($this->customer->homeAddress);
        $this->billing = Address::getForMount($this->customer->billingAddress);
        
        // Set homeIsBilling based on address comparison
        $this->homeIsBilling = $this->customer->home_address_id === $this->customer->billing_address_id;
        
        // Load loyalty numbers
        foreach ($this->customer->loyaltyNumbers as $loyaltyNumber) {
            $this->loyalty[] = [
                'id' => $loyaltyNumber->id, 
                'type' => $loyaltyNumber->loyalty_number_type_id, 
                'name' => $loyaltyNumber->loyalty_number, 
                'notes' => $loyaltyNumber->notes
            ];
        }

        // Load merchandise
        foreach ($this->customer->customerMerchandises as $merchandise) {
            $this->merchandise[] = [
                'id' => $merchandise->id, 
                'category' => $merchandise->merchandise_category_id, 
                'size' => $merchandise->size, 
                'other_details' => $merchandise->other_details
            ];
        }

        // Load frequent flyer data
        $this->airlineFrequentFlyers = AirlineFrequentFlyers::all();
        $this->airlineFrequentFlyersId = $this->customer->airline_frequent_flyers_id;
        $this->membershipNumber = $this->customer->membership;
    }

    public function addLoyaltyNumber(): void
    {
        $this->loyalty[] = ['id' => null, 'type' => null, 'name' => null, 'notes' => null];
    }

    public function removeLoyaltyNumber(int $key): void
    {
        if (array_key_exists($key, $this->loyalty)) {
            if ($this->loyalty[$key]['id'] !== null) {
                $this->loyaltyToDelete[] = $key;
            }
            unset($this->loyalty[$key]);
        }
        $this->render();
    }

    public function addMerchandise(): void
    {
        $this->merchandise[] = ['id' => null, 'category' => null, 'size' => null, 'other_details' => null];
    }

    public function removeMerchandise(int $key): void
    {
        if (array_key_exists($key, $this->merchandise)) {
            if ($this->merchandise[$key]['id'] !== null) {
                $this->merchandiseToDelete[] = $this->merchandise[$key]['id'];
            }
            unset($this->merchandise[$key]);
            $this->merchandise = array_values($this->merchandise);
        }
        $this->render();
    }

    public function updatedHomeIsBilling($value): void
    {
        if ($value) {
            $this->syncBillingToHome();
        }
    }

    public function syncBillingToHome(): void
    {
        $this->billing->address_line_1 = $this->home->address_line_1;
        $this->billing->address_line_2 = $this->home->address_line_2;
        $this->billing->town = $this->home->town;
        $this->billing->region = $this->home->region;
        $this->billing->country_id = $this->home->country_id;
        $this->billing->postcode = $this->home->postcode;
    }

    public function save(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Handle profile picture
            if ($this->profilePicture !== null) {
                $this->customer->profile_picture = store_file($this->profilePicture, $this->customer->profile_picture);
            }

            // Handle password
            if (!empty($this->password)) {
                $this->customer->password = Hash::make($this->password);
            }

            // Handle email
            if (empty(trim($this->customer->email_address))) {
                $this->customer->email_address = null;
            }

            // Save addresses
            $this->home->save();
            $this->billing->save();

            // Set address relationships
            if ($this->homeIsBilling) {
                $this->customer->home_address_id = $this->billing->id;
                $this->customer->billing_address_id = $this->billing->id;
            } else {
                $this->customer->home_address_id = $this->home->id;
                $this->customer->billing_address_id = $this->billing->id;
            }

            // Set frequent flyer data
            $this->customer->airline_frequent_flyers_id = $this->airlineFrequentFlyersId;
            $this->customer->membership = $this->membershipNumber;

            // Save customer
            $this->customer->save();

            // Handle loyalty numbers
            foreach ($this->loyaltyToDelete as $id) {
                LoyaltyNumber::find($id)?->delete();
            }

            foreach ($this->loyalty as $data) {
                if (empty($data['type']) && empty($data['name'])) { continue; }
                
                if ($data['id'] !== null) {
                    $loyalty = LoyaltyNumber::find($data['id']);
                } else {
                    $loyalty = new LoyaltyNumber();
                }
                
                $loyalty->loyalty_number_type_id = $data['type'] ?? null;
                $loyalty->loyalty_number = $data['name'] ?? null;
                $loyalty->notes = $data['notes'] ?? null;
                $loyalty->customer_id = $this->customer->id;
                $loyalty->save();
            }

            // Handle merchandise
            foreach ($this->merchandiseToDelete as $id) {
                CustomerMerchandise::find($id)?->delete();
            }

            foreach ($this->merchandise as $data) {
                if (empty($data['category']) && empty($data['name'])) { continue; }
                
                if ($data['id'] !== null) {
                    $merchandise = CustomerMerchandise::find($data['id']);
                } else {
                    $merchandise = new CustomerMerchandise();
                }
                
                $merchandise->merchandise_category_id = $data['category'] ?? null;
                $merchandise->size = $data['size'] ?? null;
                $merchandise->other_details = $data['other_details'] ?? null;
                $merchandise->customer_id = $this->customer->id;
                $merchandise->save();
            }

            DB::commit();

            // Trigger events and notifications
            event(new CustomerEditedEvent($this->customer));
            $this->customer->createNotification(
                NotificationType::CUSTOMER_UPDATED, 
                'Customer details updated via dashboard', 
                $this->customer
            );

            session()->flash('message', 'Customer details updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Customer update failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to update customer details. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.customer.details-form', [
            'countries' => Country::all(),
            'passportLocked' => $this->customer->repository->isPassportLocked(),
        ]);
    }

    public function rules()
    {
        $rules = [
            'customer.title' => 'nullable|string',
            'customer.first_name' => 'required|string',
            'customer.middle_names' => 'nullable|string',
            'customer.last_name' => 'required|string',
            'customer.date_of_birth' => 'required|date|date_format:Y-m-d',
            'customer.gender' => 'nullable|string',
            'customer.mobile_number' => 'required|string',
            'customer.other_phone_number' => 'nullable|string',
            'customer.nationality' => 'nullable|string',
            'customer.email_address' => ['nullable', 'string', 'email:rfc,dns', Rule::unique('customers', 'email_address')->ignore($this->customer->id)],
            'customer.emergency_contact_name' => 'nullable|string',
            'customer.emergency_contact_relationship' => 'nullable|string',
            'customer.emergency_contact_telephone' => 'nullable|string',
            'password' => ['nullable', Password::default()],
            
            'home.address_line_1' => 'required|string',
            'home.address_line_2' => 'nullable|string',
            'home.town' => 'required|string',
            'home.region' => 'required|string',
            'home.country_id' => 'required|int|exists:countries,id',
            'home.postcode' => 'required|string',
            
            'billing.address_line_1' => 'required|string',
            'billing.address_line_2' => 'nullable|string',
            'billing.town' => 'required|string',
            'billing.region' => 'required|string',
            'billing.country_id' => 'required|int|exists:countries,id',
            'billing.postcode' => 'required|string',
            
            'customer.passport_first_name' => 'nullable|string',
            'customer.passport_middle_name' => 'nullable|string',
            'customer.passport_last_name' => 'nullable|string',
            'customer.passport_number' => 'nullable|string',
            'customer.passport_country_of_issue' => 'nullable|string',
            'customer.passport_issue_date' => 'nullable|string',
            'customer.passport_expiry_date' => 'nullable|string',
            'customer.internal_notes' => 'nullable|string',
            'customer.external_notes' => 'nullable|string',
            'customer.dietary_notes' => 'nullable|string',
            'customer.mobility_notes' => 'nullable|string',
            
            'loyalty.*.type' => 'nullable|required_with:loyalty.*.name,loyalty.*.notes|int|exists:loyalty_number_types,id',
            'loyalty.*.name' => 'nullable|required_with:loyalty.*.notes,loyalty.*.type|string',
            'loyalty.*.notes' => 'nullable|required_with:loyalty.*.name,loyalty.*.type|string',

            'merchandise.*.category' => 'nullable|required_with:merchandise.*.size,merchandise.*.other_details|int|exists:merchandise_categories,id',
            'merchandise.*.size' => 'nullable|required_with:merchandise.*.other_details,merchandise.*.category|string',
            'merchandise.*.other_details' => 'nullable|required_with:merchandise.*.size,merchandise.*.category|string',

            'airlineFrequentFlyersId' => 'nullable|int|exists:airline_frequent_flyers,id',
            'membershipNumber' => 'nullable|string',
        ];

        // Add passport validation rules if not locked
        if (!$this->customer->repository->isPassportLocked()) {
            $rules += [
                'customer.passport_first_name' => 'nullable|string',
                'customer.passport_last_name' => 'nullable|string',
                'customer.passport_number' => 'nullable|string',
                'customer.passport_country_of_issue' => 'nullable|string',
                'customer.passport_issue_date' => 'nullable|date',
                'customer.passport_expiry_date' => 'nullable|date',
            ];
        }

        return $rules;
    }
}