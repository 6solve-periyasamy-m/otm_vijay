<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEditedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\DetailsRequest;
use App\Models\Customer\Customer;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\CustomerDashboardRepository;
use App\View\Components\Admin\Section\Header\Detail;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Log;
use Storage;
use Throwable;

class CustomerDetailsController extends Controller
{
    public function edit()
    {
        return view('pages.customer.details', ['customer' => CustomerAuthenticationRepository::getCustomer(),
            'editable' => CustomerDashboardRepository::getEditableCustomers(CustomerAuthenticationRepository::getCustomer())]);
    }

    public function editOther(Customer $customer)
    {
        if (!CustomerDashboardRepository::canEditCustomer(CustomerAuthenticationRepository::getCustomer(), $customer)) abort(404);
        return view('pages.customer.details', ['customer' => $customer, 'other' => true,
            'editable' => CustomerDashboardRepository::getEditableCustomers(CustomerAuthenticationRepository::getCustomer())]);
    }

    public function update(DetailsRequest $request)
    {
        $customer = CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);

        $customer->update($request->getCustomerDetails());
        $customer->homeAddress->repository->update($request->getHomeAddress());
        $customer->billingAddress->repository->update($request->getBillingAddress());

        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $customer->profile_picture = store_file($request->profile_picture, $customer->profile_picture);
        }

        if ($request->has('new_password') && !empty($request->new_password)) {
            $customer->password = Hash::make($request->new_password);
        }

        $customer->save();
        event(new CustomerEditedEvent($customer));

        return redirect()->route('customer.edit');
    }

    public function updateOther(DetailsRequest $request, Customer $customer)
    {
        if (!isset($customer)) abort(404);
        if (!CustomerDashboardRepository::canEditCustomer(CustomerAuthenticationRepository::getCustomer(), $customer)) abort(404);

        $customer->update($request->getCustomerDetails());
        $customer->homeAddress->repository->update($request->getHomeAddress());
        $customer->billingAddress->repository->update($request->getBillingAddress());

        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $customer->profile_picture = store_file($request->profile_picture, $customer->profile_picture);
        }

        $customer->save();
        event(new CustomerEditedEvent($customer));
        return redirect()->route('customer.edit.other', ['customer' => $customer,]);
    }
}
