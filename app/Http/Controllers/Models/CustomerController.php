<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\AddressParent;
use App\Models\Customer;
use App\Repository\LocationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    const HOME_RULES = ['home_address_line_1' => 'required', 'home_country' => 'required|exists:countries,id', 'home_postcode' => 'required'];
    const BILLING_RULES = ['billing_address_line_1' => 'required_unless:home_is_billing,on', 'billing_country' => 'required_unless:home_is_billing,on|nullable|exists:countries,id', 'billing_postcode' => 'required_unless:home_is_billing,on'];

    public function index()
    {
        return view('pages.models.customers.table', ['customers' => Customer::all(),]);
    }

    public function create()
    {
        return view('pages.models.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate(Customer::getValidationRules());
        $request->validate(self::HOME_RULES);
        $request->validate(self::BILLING_RULES);
        $customer = Customer::make([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'email_address' => $request->input('email_address'),
            'password' => Hash::make($request->input('password')),
            'gender' => $request->input('gender'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
            'emergency_contact_telephone' => $request->input('emergency_contact_telephone'),
            'passport_first_name' => $request->input('passport_first_name'),
            'passport_middle_name' => $request->input('passport_middle_name'),
            'passport_last_name' => $request->input('passport_last_name'),
            'passport_number' => $request->input('passport_number'),
            'passport_issue_date' => $request->input('passport_issue_date'),
            'passport_expiry_date' => $request->input('passport_expiry_date'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
            'notes' => $request->input('notes'),
            'loyalty_number' => $request->input('loyalty_number'),
        ]);
        $homeAddress = Address::create([
            'name' => $request->input('title') . ' ' . $request->input('first_name') . ' ' . $request->input('last_name'),
            'address_parent_id' => AddressParent::getParentId('customer'),
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'country_id' => $request->input('home_country'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->home_address_id = $homeAddress->id;
        if ($request->input('home_is_billing') == 'on') {
            $customer->billing_address_id = LocationsRepository::cloneAddressToAddress($homeAddress, AddressParent::getParentId('customer'))->id;
        } else {
            $billingAddress = Address::create([
                'name' => $request->input('title') . ' ' . $request->input('first_name') . ' ' . $request->input('last_name'),
                'address_parent_id' => AddressParent::getParentId('customer'),
                'address_line_1' => $request->input('billing_address_line_1'),
                'address_line_2' => $request->input('billing_address_line_2'),
                'town' => $request->input('billing_town'),
                'region' => $request->input('billing_region'),
                'country_id' => $request->input('billing_country'),
                'postcode' => $request->input('billing_postcode'),
            ]);
            $customer->billing_address_id = $billingAddress->id;
        }
        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $customer->profile_picture = $request->file('profile_picture')->storePublicly('uploads/images/customers');
        }
        $customer->save();
        return redirect()->route('customers.view', ['customer' => $customer,]);
    }

    public function view(Customer $customer)
    {
        return view('pages.models.customers.view', ['customer' => $customer,]);
    }

    public function edit(Customer $customer)
    {
        return view('pages.models.customers.update', ['customer' => $customer,]);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate(Customer::getValidationRules());
        $customer->update([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'email_address' => $request->input('email_address'),
            'password' => Hash::make($request->input('password')),
            'gender' => $request->input('gender'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
            'emergency_contact_telephone' => $request->input('emergency_contact_telephone'),
            'passport_first_name' => $request->input('passport_first_name'),
            'passport_middle_name' => $request->input('passport_middle_name'),
            'passport_last_name' => $request->input('passport_last_name'),
            'passport_number' => $request->input('passport_number'),
            'passport_issue_date' => $request->input('passport_issue_date'),
            'passport_expiry_date' => $request->input('passport_expiry_date'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
            'notes' => $request->input('notes'),
            'loyalty_number' => $request->input('loyalty_number'),
        ]);
        $customer->homeAddress->update([
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'country' => $request->input('home_country'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->homeAddress->save();
        if ($request->input('home_is_billing') == 'on') {
            LocationsRepository::cloneAddressToAddress($customer->homeAddress, AddressParent::getParentId('customer'), $customer->billingAddress);
        } else {
            $customer->billingAddress->update([
                'address_line_1' => $request->input('billing_address_line_1'),
                'address_line_2' => $request->input('billing_address_line_2'),
                'town' => $request->input('billing_town'),
                'region' => $request->input('billing_region'),
                'country' => $request->input('billing_country'),
                'postcode' => $request->input('billing_postcode'),
            ]);
            $customer->billingAddress->save();
        }
        if ($request->has('profile_picture') && $request->file('profile_picture') != null) {
            $customer->profile_picture = $request->file('profile_picture')->storePublicly('uploads/images/customers');
        }
        $customer->save();
        return redirect()->route('customers.view', ['customer' => $customer,]);
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orderCustomers()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Customer', 'parent' => 'Order']));
        }
        $customer->delete();
        return redirect()->route('customers.all');
    }
}
