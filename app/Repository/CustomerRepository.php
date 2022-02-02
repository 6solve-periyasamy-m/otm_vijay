<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function __construct();
    public function isRegistered($email);
    public function create(array $customer);
    public function update(array $customer);
}

class CustomerRepository implements CustomerRepositoryInterface
{
    protected $model;
    private $fields;
    private $logging = true;

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
        $email = $customer['email_address'];
        if (empty($email)) {
            throw new \Exception('Can not create a customer without an email address');
        }
        $emailUsed = $this->model->where('email_address', $email)->get();
        if ($emailUsed->count()) {
            throw new \Exception('Can not create a customer with an email address that already exists');
        }
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
        $customerRecord = $this->model->where('email_address', $customer['email_address'])->first();
        if (empty($customerRecord) || $customerRecord->count() === 0) {
            throw new \Exception('Can not update a customer with an email address that does not exist');
        }
        foreach ($this->fields as $field) {
            if (isset($customer[$field]) && $customer[$field] !== $this->model->$field) {
                // $this->model->$field = $customer[$field];
                $customerRecord->$field = $customer[$field];
                $this->logging && Log::info('check model', [$field, $customer[$field], $this->model->$field]);
            }
        }
        // force the billing_address_id (as it is not in the request) 
        $customerRecord->billing_address_id = $customer['billing_address_id'];
        try {
            $customerRecord->save();
            $this->logging && Log::info('customer saved: ', $this->model->toArray());
            return $customerRecord;
        } catch (\Exception $e) {
            Log::error('error updating customer' . $e->getMessage());
        }
        return null;
    }

    public static function lookup($customer_id)
    {
        return self::get($customer_id);
    }

    public function get($customer_id)
    {
        $customer = $this->model->find($customer_id);
        
        return $customer;
    }

}
