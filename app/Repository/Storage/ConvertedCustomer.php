<?php

namespace App\Repository\Storage;

use App\Models\Customer\Customer;

class ConvertedCustomer
{
    public Customer $customer;
    public bool $travelling;
    public bool $paying;
    public array $data;

    public function __construct(Customer $customer, bool $paying = true, bool $travelling = true, array $data = [])
    {
        $this->customer = $customer;
        $this->paying = $paying;
        $this->travelling = $travelling;
        $this->data = $data;
    }
}
