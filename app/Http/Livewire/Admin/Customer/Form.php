<?php

namespace App\Http\Livewire\Admin\Customer;

use App\Events\Customer\CustomerCreatedEvent;
use App\Events\Customer\CustomerEditedEvent;
use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\Customer;
use App\Models\Customer\LoyaltyNumber;
use App\Models\Location\Address;
use Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Form extends Component
{
    use SendsEvents, LivewireForm;

    public Customer|null $customer;
    public Address $home;
    public Address $billing;
    public string $password = "";
    public UploadedFile|null $image = null;
    public bool $accordion = false;
    public array $loyalty = [];
    public array $loyaltyToDelete = [];

    public function mount(Customer|int|null $customer)
    {
        $this->customer = Customer::getForMount($customer);
        $this->home = Address::getForMount($this->customer->homeAddress);
        $this->billing = Address::getForMount($this->customer->billingAddress);
        foreach ($this->customer->loyaltyNumbers as $loyaltyNumber) {
            $this->loyalty[] = ['id' => $loyaltyNumber->id, 'type' => $loyaltyNumber->loyalty_number_type_id, 'name' => $loyaltyNumber->loyalty_number];
        }
        //dd($this->loyalty);
    }

    public function addLoyaltyNumber(): void
    {
        $this->loyalty[] = ['id' => null, 'type' => null, 'name' => null];
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

    public function save(): void
    {
        $this->validate();
        if ($this->image !== null) {
            $this->customer->profile_picture = store_file($this->image, $this->customer->profile_picture);
        }
        if (!empty($this->password)) {
            $this->customer->password = Hash::make($this->password);
        }
        $this->home->save();
        $this->billing->save();
        $this->customer->home_address_id = $this->home->id;
        $this->customer->billing_address_id = $this->billing->id;
        $editing = $this->customer->id === null;
        $this->customer->save();
        if ($editing) {
            event(new CustomerCreatedEvent($this->customer));
        } else {
            event(new CustomerEditedEvent($this->customer));
        }
        foreach ($this->loyaltyToDelete as $id) {
            LoyaltyNumber::find($id)?->delete();
        }
        foreach ($this->loyalty as $data) {
            if ($data['id'] !== null) {
                $loyalty = LoyaltyNumber::find($data['id']);
            } else {
                $loyalty = new LoyaltyNumber();
            }
            $loyalty->loyalty_number_type_id = $data['type'];
            $loyalty->loyalty_number = $data['name'];
            $loyalty->customer_id = $this->customer->id;
            $loyalty->save();
        }
        $this->closeModal();
        $this->redirect(route('customers.view', ['customer' => $this->customer,]));
    }

    public function homeToBilling(): void
    {
        $billingId = $this->billing->id;
        $this->billing = $this->home->replicate();
        $this->billing->id = $billingId;
        $this->updateValue('billing.country_id', $this->billing->country_id);
    }

    public function billingToHome(): void
    {
        $homeId = $this->home->id;
        $this->home = $this->billing->replicate();
        $this->home->id = $homeId;
        $this->updateValue('home.country_id', $this->home->country_id);
    }

    public function toggleAccordion(): void
    {
        $this->accordion = !$this->accordion;
    }

    public function render()
    {
        return view('livewire.admin.customer.form');
    }

    public function rules()
    {
        return [
            'customer.title' => 'nullable|string',
            'customer.first_name' => 'required|string',
            'customer.middle_names' => 'nullable|string',
            'customer.last_name' => 'required|string',
            'customer.date_of_birth' => 'nullable|date|date_format:Y-m-d',
            'customer.gender' => 'nullable|string',
            'customer.mobile_number' => 'nullable|string',
            'customer.other_phone_number' => 'nullable|string',
            'customer.nationality' => 'nullable|string',
            'customer.email_address' => ['nullable', 'string', 'email:rfc,dns', Rule::unique('customers', 'email_address')->ignore($this->customer->id),],
            'customer.loyalty_number' => 'nullable|string',
            'customer.emergency_contact_name' => 'nullable|string',
            'customer.emergency_contact_relationship' => 'nullable|string',
            'customer.emergency_contact_telephone' => 'nullable|string',
            'password' => ['nullable', Password::default(),],
            'home.address_line_1' => 'nullable|string',
            'home.address_line_2' => 'nullable|string',
            'home.town' => 'nullable|string',
            'home.region' => 'nullable|string',
            'home.country_id' => 'nullable|int|exists:countries,id',
            'home.postcode' => 'nullable|string',
            'billing.address_line_1' => 'nullable|string',
            'billing.address_line_2' => 'nullable|string',
            'billing.town' => 'nullable|string',
            'billing.region' => 'nullable|string',
            'billing.country_id' => 'nullable|int|exists:countries,id',
            'billing.postcode' => 'nullable|string',
            'customer.passport_first_name' => 'nullable|string',
            'customer.passport_middle_name' => 'nullable|string',
            'customer.passport_last_name' => 'nullable|string',
            'customer.passport_number' => 'nullable|string',
            'customer.passport_country_of_issue' => 'nullable|string',
            'customer.passport_issue_date' => 'nullable|string',
            'customer.passport_expiry_date' => 'nullable|string',
            'customer.hat_size_id' => 'nullable|int|exists:hat_sizes,id',
            'customer.t_shirt_size_id' => 'nullable|int|exists:t_shirt_sizes,id',
            'customer.internal_notes' => 'nullable|string',
            'customer.external_notes' => 'nullable|string',
            'customer.dietary_notes' => 'nullable|string',
            'customer.mobility_notes' => 'nullable|string',
            'loyalty.*.type' => 'required|int|exists:loyalty_number_types,id',
            'loyalty.*.name' => 'required|string',
        ];
    }
}
