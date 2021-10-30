<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function __construct();
    public function create(array $customer);
}

class CustomerRepository implements CustomerRepositoryInterface
{
    protected $model;
    private $fields;

    public function __construct()
    {
        $this->model = new Customer();
        $this->fields = $this->model->getFields();
        return $this;
    }

    public function isRegistered($email) {
        $exists = $this->model->where('email_address', $email)->count();
        return $exists > 0;
    }

    public function create(array $customer)
    {
        foreach ($this->fields as $field) {
            if (isset($customer[$field])) {
                $this->model->$field = $customer[$field];
            }
        }
        try {
            $this->model->save();
            return $this->model;
        } catch (\Exception $e) {
            Log::error('Error creating customer record, details: '. $e->getMessage());
        }
        return null;
    }

    public function update(array $customer) {
        foreach ($this->fields as $field) {
            if (isset($customer[$field]) && $customer[$field] !== $this->model->$field) {
                $this->model->field = $customer[$field];
            }
        }
        try {
            $this->model->save();
        } catch (\Exception $e) {
            Log::debug('error updating customer' . $e->getMessage());
        }
    }
}
