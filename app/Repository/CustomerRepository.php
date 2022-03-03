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

    public function create(array $customerData)
    {
        $email = $customerData['email_address'];
        if (empty($email)) {
            Log::warning('Customer::create creating a customer without an email address', [$customerData]);
        } else {
            $emailUsed = $this->model->where('email_address', $email)->get();
            if ($emailUsed->count()) {
                throw new \Exception('Can not create a customer with an email address that already exists');
            }
        }
        foreach ($this->fields as $field) {
            if (isset($customerData[$field])) {
                $this->model->$field = $customerData[$field];
            }
        }
        try {
            $this->model->save();
            $this->logging && Log::debug('CustomerRepo: create returning customer: ', [$this->model]);
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
                $this->logging && Log::info('check field ', [$field, $customer[$field], $this->model->$field]);
            } else {
                $this->logging && Log::info('no update data for field ', [$field, $this->model->$field]);
            }
        }
        try {
            $customerRecord->save();
            $this->logging && Log::info('customer saved: ', $this->model->toArray());
            return $customerRecord;
        } catch (\Exception $e) {
            Log::error('error updating customer' . $e->getMessage());
        }
        return null;
    }

    /**
     * get: return customer object for ID
     *
     * @param INT $customer_id
     * @return Customer
     */
    public function get($customer_id)
    {
        $customer = $this->model->find($customer_id);
        
        return $customer;
    }

    /**
     * lookup: static version of get
     *
     * @param INT $customer_id
     * @return Customer
     */
    public static function lookup($customer_id)
    {
        return Customer::find($customer_id);
    }
}
