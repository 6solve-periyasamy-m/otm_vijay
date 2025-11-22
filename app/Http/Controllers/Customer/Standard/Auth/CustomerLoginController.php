<?php

namespace App\Http\Controllers\Customer\Standard\Auth;

use App\Http\Controllers\Controller;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class CustomerLoginController extends Controller
{
    public function show(Request $request)
    {
        if (CustomerAuthenticationRepository::getCustomer() !== null) return redirect()->route('customer.portal');
        if (isset($request->from)) {
            $to = url($request->from);
        } else {
            $to = route('customer.portal');
        }
        Session::put('url.intended', $to);
        return view('pages.customer.standard.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt(['email_address' => $request->email, 'password' => $request->password])) {
            return redirect()->intended($request->from ?? route('customer.portal'));
        }
        return back()->withErrors('Could not authenticate with those credentials')->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
    }
}
