<?php

namespace App\Http\Livewire;

use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\System\Brand;
use LivewireUI\Modal\ModalComponent;

class BrandForm extends ModalComponent
{
    public Brand $brand;
    public bool $useSystemAddress = true;
    public string|null $adLine1;
    public string|null $adLine2;
    public string|null $adTown;
    public string|null $adRegion;
    public string|null $adCountry;
    public string|null $adPostcode;

    public function mount(Brand|null $brand)
    {
        if ($brand === null) {
            $brand = new Brand();
        }
        $this->brand = $brand;
    }

    private function getAddress(): Address
    {
        return new Address([
            'name' => $this->brand?->name ?? 'Unknown Brand Address',
            'address_parent_id' => AddressParent::getParentId('other'),
            'address_line_1' => $this->adLine1,
            'address_line_2' => $this->adLine2,
            'town' => $this->adTown,
            'region' => $this->adRegion,
            'country_id' => $this->adCountry,
            'postcode' => $this->adPostcode,
        ]);
    }

    public function submit()
    {
        if ($this->useSystemAddress) {
            $this->brand->repository->updateAddress(null);
        } else {
            $this->brand->repository->updateAddress($this->getAddress());
        }
        $this->brand->save();
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
            'brand.url' => 'required',
            'brand.facebook' => 'required',
            'brand.twitter' => 'required',
            'brand.instagram' => 'required',
        ];
    }
}
