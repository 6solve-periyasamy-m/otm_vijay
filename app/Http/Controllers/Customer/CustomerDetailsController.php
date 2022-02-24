<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerDetailsController extends Controller
{
    public function show()
    {
        return view('pages.customer.details', ['customer' => CustomerPortalController::getCustomer(),]);
    }

    public function edit()
    {
        return view('pages.customer.edit', ['customer' => CustomerPortalController::getCustomer(),]);
    }

    public function update(Request $request, Customer $customer)
    {
        // TODO: (Celeste) Implement
        return redirect()->route('customer.details', ['customer' => $customer,]);
    }
}
