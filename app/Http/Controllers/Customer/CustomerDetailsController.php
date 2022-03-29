<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerEditedEvent;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OrderCustomer;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerDashboardRepository;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Log;
use Storage;
use Throwable;

class CustomerDetailsController extends Controller
{
    private function getValidationRules(): array
    {
        return [
            'current_password' => [
                'nullable',
                'required_with:new_password',
                'current_password'
            ],
            'new_password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required|date',
            'mobile_number' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact_relationship' => 'required',
            'emergency_contact_telephone' => 'required',
        ];
    }

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

    public function update(Request $request)
    {
        $customer = CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);
        $request->validate($this->getValidationRules());
        $customer->update([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'gender' => $request->input('gender'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
            'emergency_contact_telephone' => $request->input('emergency_contact_telephone'),
            'passport_first_name' => $request->input('passport_first_name'),
            'passport_middle_name' => $request->input('passport_middle_name'),
            'passport_last_name' => $request->input('passport_last_name'),
            'passport_number' => $request->input('passport_number'),
            'passport_expiry_date' => $request->input('passport_expiry_date'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
        ]);
        $customer->homeAddress->update([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Home)',
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->homeAddress->save();
        $customer->billingAddress->update([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Billing)',
            'address_line_1' => $request->input('billing_address_line_1'),
            'address_line_2' => $request->input('billing_address_line_2'),
            'town' => $request->input('billing_town'),
            'region' => $request->input('billing_region'),
            'postcode' => $request->input('billing_postcode'),
        ]);
        $customer->billingAddress->save();
        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            if (!empty($customer->profile_picture)) {
                try {
                    Storage::delete($customer->profile_picture);
                } catch (Throwable $e) {
                    Log::error($e);
                }
            }
            $customer->profile_picture = $request->file('profile_picture')->storePublicly('uploads/images/customers');
        }
        if ($request->has('new_password') && !empty($request->input('new_password'))) {
            $customer->password = Hash::make($request->input('new_password'));
        }
        $customer->save();
        event(new CustomerEditedEvent($customer));
        return redirect()->route('customer.edit');
    }
    public function updateOther(Customer $customer, Request $request)
    {
        if (!isset($customer)) abort(404);
        if (!CustomerDashboardRepository::canEditCustomer(CustomerAuthenticationRepository::getCustomer(), $customer)) abort(404);
        $request->validate($this->getValidationRules());
        $customer->update([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'gender' => $request->input('gender'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
            'emergency_contact_telephone' => $request->input('emergency_contact_telephone'),
            'passport_first_name' => $request->input('passport_first_name'),
            'passport_middle_name' => $request->input('passport_middle_name'),
            'passport_last_name' => $request->input('passport_last_name'),
            'passport_number' => $request->input('passport_number'),
            'passport_expiry_date' => $request->input('passport_expiry_date'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
        ]);
        $customer->homeAddress->update([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Home)',
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->homeAddress->save();
        $customer->billingAddress->update([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Billing)',
            'address_line_1' => $request->input('billing_address_line_1'),
            'address_line_2' => $request->input('billing_address_line_2'),
            'town' => $request->input('billing_town'),
            'region' => $request->input('billing_region'),
            'postcode' => $request->input('billing_postcode'),
        ]);
        $customer->billingAddress->save();
        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $customer->profile_picture = $request->file('profile_picture')->storePublicly('uploads/images/customers');
        }
        $customer->save();
        event(new CustomerEditedEvent($customer));
        return redirect()->route('customer.edit.other', ['customer' => $customer,]);
    }
}
