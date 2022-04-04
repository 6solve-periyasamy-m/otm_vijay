<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repository\CustomerAuthenticationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLoginController extends Controller
{
    public function show()
    {
        if (CustomerAuthenticationRepository::getCustomer() !== null) return redirect()->route('customer.portal');
        return view('pages.customer.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt(['email_address' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('customer.portal'));
        }
        return back()->withErrors('Could not authenticate with those credentials')->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
    }
}
