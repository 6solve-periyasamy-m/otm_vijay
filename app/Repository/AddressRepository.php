<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Address;

interface AddressRepositoryInterface 
{
    public function __construct();
    public function get($address_id);
    public function create(Array $address);
}

class AddressRepository implements AddressRepositoryInterface
{
    private $model;
    private $fields;
    private $logging = false;

    public function __construct()
    {
        $this->model = new Address();
        $this->fields = $this->model->getFillable();
        return $this;
    }

    public function get($address_id)
    {
        $address = $this->model->find($address_id);
        if (isset($address)) {
            return $address->toArray();
        }
        return null;
    }

    public function create(Array $address, $type = 'home')
    {
        $address['address_parent_id'] = 1;
        $address['location_type_id'] = 1;
        $address['name'] = ucfirst($type . ' address');
        foreach($this->fields as $field) {
            $typedField = $type . '_' . $field;
            $this->model->$field = $address[$field];
        }
        try {
            $this->model->save();
            return $this->model;
        } catch (\Exception $e) {
            Log::error("!!! Can not save an address ".$e->getMessage().", data: ", [$address]);
        }
        return null;
    }

    /**
     * update 
     *
     * @param array $address (array of new fields to update model)
     * @return Object
     */
    public function update(array $address) {
        //Log::debug('AddressRepo: address update with ', [$address]);
        if (empty($address['id'])) {
            throw new \Exception('ERROR: address update does not have an address_id');
        }
        $currentAddress = $this->model->find($address['id']);
        if (empty($currentAddress)) {
            throw new \Exception('address update can not load the current address');
        }
        foreach ($this->fields as $field) {
            if (isset($address[$field]) && $address[$field] !== $currentAddress->$field) {
                $currentAddress->$field = $address[$field];
            } else {
                if ($this->logging) Log::debug('&&& field not set or not changed', [$field, $address]);
            }
        }
        try {
            $currentAddress->save();
            
        } catch (\Exception $e) {
            Log::error('error updating customer' . $e->getMessage());
        }
        return $currentAddress;
    }
}
