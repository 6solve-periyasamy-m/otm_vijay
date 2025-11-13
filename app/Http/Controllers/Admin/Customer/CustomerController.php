<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Events\Customer\CustomerRemovedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAsCustomerRequest;
use App\Models\Customer\Customer;
use App\Exports\CustomersExport;
use Excel;
use Auth;

class CustomerController extends Controller
{

    public function index()
    {
        return view('pages.models.customers.table', ['customers' => Customer::with('homeAddress',)->get(),]);
    }

    public function create()
    {
        return view('pages.admin.customer.form');
    }

    public function login(LoginAsCustomerRequest $request)
    {
        Auth::guard('customer')->loginUsingId($request->customer_id);
        return redirect()->route('customer.portal');
    }

    public function forget(Customer $customer)
    {
        $customer->repository->forget();
        return redirect()->route('customers.all');
    }

    public function view(Customer $customer)
    {
        return view('pages.models.customers.view', ['customer' => $customer,]);
    }

    public function edit(Customer $customer)
    {
        return view('pages.admin.customer.form', ['customer' => $customer,]);
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orderCustomers()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Customer', 'parent' => 'Order']));
        }
        $customer->delete();
        event(new CustomerRemovedEvent($customer));
        return redirect()->route('customers.all');
    }
    public function export()
    {
        $extension = 'xlsx';
        $filename = 'customer-list-' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
        return Excel::download(new CustomersExport, $filename);
    }
}
