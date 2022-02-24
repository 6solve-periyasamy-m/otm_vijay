<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repository\BookingRepository;
use App\Repository\CustomerAuthenticationRepository;
use Auth;

class CustomerPortalController extends Controller
{
    public function show(Customer $customer)
    {
        return view('pages.customer.portal', ['customer' => CustomerAuthenticationRepository::getCustomer(),]);
    }

    public function showAtol()
    {
        return view('pages.customer.atol', ['customer' => CustomerAuthenticationRepository::getCustomer(),]);
    }


}
