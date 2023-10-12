<?php

namespace App\Http\Livewire\Admin\Organization;

use App\Http\Livewire\SendsEvents;
use App\Models\Customer\Organization;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /**
     * @var Organization $organization
     */
    public $organization;
    public Address $delivery;
    public Address $billing;
    public bool $billingIsDelivery = false;

    public function mount(Organization|int|null $organization = null): void
    {
        if (is_int($organization)) {
            $organization = Organization::find($organization);
        }
        if ($organization === null) {
            $organization = new Organization();
        }
        $this->organization = $organization;
        $this->delivery = $organization->deliveryAddress ?? new Address(['parent' => AddressParent::ORGANIZATION]);
        $this->billing = $organization->billingAddress ?? new Address(['parent' => AddressParent::ORGANIZATION]);
    }

    public function save(): void
    {
        if ($this->billingIsDelivery) {
            $billingId = $this->billing->id;
            $this->billing = $this->delivery->replicate(['id']);
            $this->billing->id = $billingId;
        }
        $this->delivery->name = "{$this->organization->name} - Delivery Address";
        $this->billing->name = "{$this->organization->name} - Billing Address";
        $this->delivery->save();
        $this->billing->save();
        $this->organization->delivery_address_id = $this->delivery->id;
        $this->organization->billing_address_id = $this->billing->id;
        $this->organization->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.organization.form');
    }

    public function rules(): array
    {
        return [
            'organization.name' => 'required',
            'organization.contact_number' => 'nullable',
            'organization.contact_email' => 'nullable',
            'organization.internal_notes' => 'nullable',
            'organization.external_notes' => 'nullable',
            'delivery.address_line_1' => 'nullable',
            'delivery.address_line_2' => 'nullable',
            'delivery.town' => 'nullable',
            'delivery.region' => 'nullable',
            'delivery.country_id' => 'nullable',
            'delivery.postcode' => 'nullable',
            'billing.address_line_1' => 'nullable',
            'billing.address_line_2' => 'nullable',
            'billing.town' => 'nullable',
            'billing.region' => 'nullable',
            'billing.country_id' => 'nullable',
            'billing.postcode' => 'nullable',
        ];
    }
}
