<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class CustomerRegisterController extends Controller
{
    public function show()
    {
        if (CustomerAuthenticationRepository::getCustomer() !== null) return redirect()->route('customer.portal');
        return view('pages.customer.auth.register');
    }

    public function register(Request $request)
    {
        $customer = Customer::where('email_address', '=', $request->input('email'))->first();
        if (!isset($customer)) return back()->withErrors(['msg' => 'No bookings found with that email address',]);
        if (isset($customer->password)) return back()->withErrors(['msg' => 'That email address is already registered',]);
        $request->validate($this->getRegistrationValidationRules());
        $customer->update([
            'password' => Hash::make($request->input('password')),
        ]);
        $customer->save();
        return redirect()->route('customer.portal');
    }

    // Customers can only register if they have a booking

    private function getRegistrationValidationRules(): array
    {
        return [
            'email' => 'required|exists:customers,email_address|email:rfc,dns',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }
}
