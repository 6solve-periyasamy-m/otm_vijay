<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repository\BookingRepository;
use Auth;

class CustomerPortalController extends Controller
{
    public static function getCustomer(): ?Customer
    {
        return Customer::find(Auth::guard('customer')->id());
    }

    public function show(Customer $customer)
    {
        return view('pages.customer.portal', ['customer' => $this->getCustomer(),]);
    }

    public function showAtol()
    {
        return view('pages.customer.atol', ['customer' => $this->getCustomer(),]);
    }


}
