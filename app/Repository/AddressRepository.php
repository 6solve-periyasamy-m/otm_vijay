<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Address;

interface AddressRepositoryInterface 
{
    public function __construct();
    public function create(Array $address);
}

class AddressRepository implements AddressRepositoryInterface
{
    protected $model;
    private $fields;

    public function __construct()
    {
        $this->model = new Address();
        $this->fields = $this->model->getFillable();
        return $this;
    }

    public function create(Array $address)
    {
        Log::info('save address', $address);
        foreach($this->fields as $field) {
            $this->model->$field = $address[$field];
        }
        try {
            $this->model->save();
            Log::info('saved address', $address);
        } catch (\Exception $e) {
            Log::error("Can not save an address, data: ", implode($address));
            return null;
        }
        return $this->model;
    }
}
