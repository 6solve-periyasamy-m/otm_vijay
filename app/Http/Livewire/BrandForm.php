<?php

namespace App\Http\Livewire;

use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Location\Country;
use App\Models\System\Brand;
use LivewireUI\Modal\ModalComponent;

class BrandForm extends ModalComponent
{
    public Brand $brand;
    public bool $useSystemAddress = false;
    public Address $address;
    public string $country;
    public function mount(Brand|null $brand)
    {
        if ($brand === null) {
            $brand = new Brand();
        }
        $this->brand = $brand;
        $this->address = $brand?->address ?? new Address();
        $this->country = $this->address->country?->name ?? "";
    }

    public function submit()
    {
        $create = !isset($this->brand?->id);
        if ($this->useSystemAddress) {
            $this->brand->repository->updateAddress(null);
        } else {
            $this->address->name = $this->brand->name;
            $this->address->address_parent_id = AddressParent::getParentId('other');
            $this->address->country_id = Country::where('name', 'like', $this->country)->first()?->id;
            $this->brand->repository->updateAddress($this->address);
        }
        $this->brand->save();
        if ($create) {
            $this->emit('brandCreated', $this->brand->id);
        } else {
            $this->emit('brandUpdated', $this->brand->id);
        }
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.brand-form');
    }

    protected function rules()
    {
        return [
            'brand.name' => 'required',
            'brand.email' => 'required',
            'brand.phone' => 'required',
            'brand.url' => 'nullable',
            'brand.facebook' => 'nullable',
            'brand.twitter' => 'nullable',
            'brand.instagram' => 'nullable',
            'address.address_line_1' => 'nullable',
            'address.address_line_2' => 'nullable',
            'address.town' => 'nullable',
            'address.region' => 'nullable',
            'address.country_id' => 'nullable',
            'address.postcode' => 'nullable',
        ];
    }
}
