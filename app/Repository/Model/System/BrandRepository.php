<?php

namespace App\Repository\Model\System;

use App\Models\Location\Address;
use App\Models\System\Brand;
use App\Repository\Abstracts\ModelRepository;

class BrandRepository extends ModelRepository
{
    protected Brand $brand;

    public function __construct(Brand $model)
    {
        $this->brand = $model;
    }

    public function updateAddress(Address|null $address)
    {
        // If no address is currently set, set it to the new address
        if ($this->brand->address_id === null) {
            if ($address === null) return;
            if ($address->id === null) {
                $address->save();
            }
            $this->brand->address_id = $address->id;
            $this->brand->save();
        }
        // If an address is set, then:
        // if null is passed, delete the brand address
        // if the ID of the passed address is the same, just save the changes
        // if a different ID is passed, copy the data to the current brand address, and delete the other id
        else {
            if ($address === null) {
                $this->brand->address->forceDelete();
                $this->brand->address_id === null;
                $this->brand->save();
                return;
            }
            if ($address->id === $this->brand->address_id) {
                $address->save();
                return;
            }
            $newId = $address->id;
            $address->id = $this->brand->address_id;
            $address->save();
            if ($newId !== null) {
                Address::find($newId)->delete();
            }
        }
    }

    public function get(): Brand
    {
        return $this->brand;
    }

    public function update(array $data): Brand
    {
        $this->brand->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->brand->save();
    }

    public function delete(): bool
    {
        return $this->brand->delete();
    }

    public function isDeleted(): bool
    {
        return true;
    }

    public function __toString(): string
    {
        return $this->brand->name;
    }
}
