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
        Log::info('Create ['.$type.'] address', $address);
        foreach($this->fields as $field) {
            $typedField = $type . '_' . $field;
            $this->model->$field = $address[$field];
        }
        try {
            $this->model->save();
            Log::info('** saved address', [$this->model]);
            return $this->model;
        } catch (\Exception $e) {
            Log::error("!!! Can not save an address, data: ", implode($address));
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
        Log::debug('&&&& address update with ', [$address]);
        if (empty($address['id'])) {
            throw new \Exception('address update does not see an address_id');
        }
        $currentAddress = $this->model->find($address['id']);
        if (empty($currentAddress)) {
            throw new \Exception('address update can not load the current address');
        }
        Log::debug('&&&& checking address fields');
        foreach ($this->fields as $field) {
            if (isset($address[$field]) && $address[$field] !== $currentAddress->$field) {
                $currentAddress->$field = $address[$field];
                Log::info('check address model', [$field, $address[$field], $this->model->$field]);
            } else {
                Log::debug('&&& field not set or not changed', [$field, $address[$field]]);
            }
        }
        try {
            $currentAddress->save();
        } catch (\Exception $e) {
            Log::debug('error updating customer' . $e->getMessage());
        }
        return $currentAddress;
    }
}
