<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEditedEvent;
use App\Http\Controllers\CustomerController;
use App\Http\Requests\Customer\DetailsRequest;
use App\Models\Customer\Customer;
use Hash;

class CustomerDetailsController extends CustomerController
{
    public function edit()
    {
        return view('pages.customer.details', [
            'customer' => $this->user(),
            'editable' => $this->user()->repository->getEditableCustomers(),
        ]);
    }

    public function editOther(Customer $customer)
    {
        if (!$this->user()->repository->canEditCustomer($customer)) abort(404);
        return view('pages.customer.details', [
            'customer' => $customer,
            'other' => true,
            'editable' => $this->user()->repository->getEditableCustomers()
        ]);
    }

    public function update(DetailsRequest $request)
    {
        $this->user()->update($request->getCustomerDetails(!$this->user()->repository->isPassportLocked()));
        $this->user()->homeAddress->repository->update($request->getHomeAddress());
        $this->user()->billingAddress->repository->update($request->getBillingAddress());

        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $this->user()->profile_picture = store_file($request->profile_picture, $this->user()->profile_picture);
        }

        if ($request->has('new_password') && !empty($request->new_password)) {
            $this->user()->password = Hash::make($request->new_password);
        }

        $this->user()->save();
        event(new CustomerEditedEvent($this->user()));

        return redirect()->route('customer.edit');
    }

    public function updateOther(DetailsRequest $request, Customer $customer)
    {
        if (!$this->user()->repository->canEditCustomer($customer)) abort(404);

        $customer->update($request->getCustomerDetails(!$customer->repository->isPassportLocked()));
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
