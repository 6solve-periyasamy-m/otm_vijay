<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEditedEvent;
use App\Http\Controllers\CustomerController;
use App\Http\Requests\Customer\DetailsRequest;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\NotificationType;
use App\Models\Location\Address;
use App\Models\Customer\AirlineFrequentFlyers;
use Hash;
use DB;
class CustomerDetailsController extends CustomerController
{
    public function edit()
    {
        $frequentFlyers = AirlineFrequentFlyers::all();
        return view('pages.customer.details', [
            'customer' => $this->user(),
            'editable' => $this->user()->repository->getEditableCustomers(),
            'frequentFlyers' => $frequentFlyers,
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
        $user = $this->user();

        DB::beginTransaction();

        try {
            if ($user->billingAddress) {
                $billingAddress = $user->billingAddress->repository->update($request->getBillingAddress());
            } else {
                $billingAddress = Address::create($request->getBillingAddress());
            }

            $homeIsBilling = (bool) $request->input('home_is_billing');
            $homeAddressId = null;

            if ($homeIsBilling) {
                $homeAddressId = $billingAddress->id;

                if ($user->homeAddress) {
                    $user->homeAddress->repository->update($request->getBillingAddress());
                } else {
                    $homeAddress = Address::create($request->getBillingAddress());
                    $homeAddressId = $homeAddress->id;
                }
            } else {
                $homeAddress = Address::create($request->getHomeAddress());
                $homeAddressId = $homeAddress->id;
            }
            $customerDetails = $request->getCustomerDetails(!$user->repository->isPassportLocked());
            $customerDetails['billing_address_id'] = $billingAddress->id;
            $customerDetails['home_address_id'] = $homeAddressId;

            $customerDetails['airline_frequent_flyers_id'] = $request->airline_frequent_flyers_id;
            $customerDetails['membership'] = $request->membership_number;

            $user->update($customerDetails);

            if ($request->hasFile('profile_picture')) {
                $user->profile_picture = store_file($request->file('profile_picture'), $user->profile_picture);
            }
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            DB::commit();

            event(new CustomerEditedEvent($user));
            $user->createNotification(NotificationType::CUSTOMER_UPDATED, 'Customer details updated via dashboard', $user);

            return redirect()->route('customer.edit');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Customer update failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Failed to update customer details. Please try again.']);
        }
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

        $customer->createNotification(NotificationType::CUSTOMER_UPDATED, 'Customer details updated via dashboard', $this->user());

        return redirect()->route('customer.edit.other', ['customer' => $customer,]);
    }
}
