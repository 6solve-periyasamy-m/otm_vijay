<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

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
        $customer = Customer::create([
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
            'home_address_id' => $request->input('home_address_id'),
            'billing_address_id' => $request->input('billing_address_id'),
        ]);
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
            'home_address_id' => $request->input('home_address_id'),
            'billing_address_id' => $request->input('billing_address_id'),
        ]);
        return redirect()->route('customers.view', ['customer' => $customer,]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.all');
    }
}
