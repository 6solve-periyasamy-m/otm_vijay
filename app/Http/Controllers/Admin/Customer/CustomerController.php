<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Events\Customer\CustomerCreatedEvent;
use App\Events\Customer\CustomerEditedEvent;
use App\Events\Customer\CustomerRemovedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAsCustomerRequest;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Address;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Log;
use Storage;
use Throwable;

class CustomerController extends Controller
{

    const HOME_RULES = ['home_address_line_1' => 'required', 'home_country' => 'required|exists:countries,id', 'home_postcode' => 'required'];
    const BILLING_RULES = ['billing_address_line_1' => 'required_unless:home_is_billing,on', 'billing_country' => 'required_unless:home_is_billing,on|nullable|exists:countries,id', 'billing_postcode' => 'required_unless:home_is_billing,on'];

    public function index()
    {
        return view('pages.models.customers.table', ['customers' => Customer::with('homeAddress',)->get(),]);
    }

    public function create()
    {
        return view('pages.models.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate(Customer::getValidationRules());
        $customer = Customer::make([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'email_address' => $request->input('email_address'),
            'gender' => $request->input('gender'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship'),
            'emergency_contact_telephone' => $request->input('emergency_contact_telephone'),
            'passport_first_name' => $request->input('passport_first_name'),
            'passport_middle_name' => $request->input('passport_middle_name'),
            'passport_last_name' => $request->input('passport_last_name'),
            'passport_country_of_issue' => $request->input('passport_country_of_issue'),
            'passport_number' => $request->input('passport_number'),
            'passport_issue_date' => $request->input('passport_issue_date'),
            'passport_expiry_date' => $request->input('passport_expiry_date'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
            'organization_id' => $request->input('organization_id'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'dietary_notes' => $request->input('dietary_notes'),
            'mobility_notes' => $request->input('mobility_notes'),
            'loyalty_number' => $request->input('loyalty_number'),
        ]);
        if (!empty($request->input('password'))) {
            $customer->password = Hash::make($request->input('password'));
        }
        $homeAddress = Address::create([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Home)',
            'parent' => AddressParent::CUSTOMER,
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'country_id' => $request->input('home_country'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->home_address_id = $homeAddress->id;
        if ($request->input('home_is_billing') == 'on') {
            $customer->billing_address_id = $homeAddress->repository->cloneToNew(AddressParent::CUSTOMER)->id;
        } else {
            $billingAddress = Address::create([
                'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Billing)',
                'parent' => AddressParent::CUSTOMER,
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
        event(new CustomerCreatedEvent($customer));
        return redirect()->route('customers.view', ['customer' => $customer,]);
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
        return view('pages.models.customers.update', ['customer' => $customer,]);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate($customer->getUpdateValidationRules());
        $customer->update([
            'title' => $request->input('title'),
            'first_name' => $request->input('first_name'),
            'middle_names' => $request->input('middle_names'),
            'last_name' => $request->input('last_name'),
            'date_of_birth' => $request->input('date_of_birth'),
            'mobile_number' => $request->input('mobile_number'),
            'other_phone_number' => $request->input('other_phone_number'),
            'email_address' => $request->input('email_address'),
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
            'passport_country_of_issue' => $request->input('passport_country_of_issue'),
            't_shirt_size_id' => $request->input('t_shirt_size_id'),
            'hat_size_id' => $request->input('hat_size_id'),
            'organization_id' => $request->input('organization_id'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'dietary_notes' => $request->input('dietary_notes'),
            'mobility_notes' => $request->input('mobility_notes'),
            'loyalty_number' => $request->input('loyalty_number'),
        ]);
        if (!empty($request->input('password'))) {
            $customer->password = Hash::make($request->input('password'));
        }
        $customer->homeAddress->update([
            'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Home)',
            'address_line_1' => $request->input('home_address_line_1'),
            'address_line_2' => $request->input('home_address_line_2'),
            'town' => $request->input('home_town'),
            'region' => $request->input('home_region'),
            'country_id' => $request->input('home_country'),
            'postcode' => $request->input('home_postcode'),
        ]);
        $customer->homeAddress->save();
        if ($request->input('home_is_billing') == 'on') {
            $customer->homeAddress->repository->cloneToNew(AddressParent::CUSTOMER, $customer->billingAddress);
        } else {
            $customer->billingAddress->update([
                'name' => $request->input('email') . ' (' . $request->input('first_name') . ' ' . $request->input('last_name') . ') (Billing)',
                'address_line_1' => $request->input('billing_address_line_1'),
                'address_line_2' => $request->input('billing_address_line_2'),
                'town' => $request->input('billing_town'),
                'region' => $request->input('billing_region'),
                'country_id' => $request->input('billing_country'),
                'postcode' => $request->input('billing_postcode'),
            ]);
            $customer->billingAddress->save();
        }
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
        $customer->save();
        event(new CustomerEditedEvent($customer));
        return redirect()->route('customers.view', ['customer' => $customer,]);
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
}
