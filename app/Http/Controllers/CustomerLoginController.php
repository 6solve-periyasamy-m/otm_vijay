<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLoginController extends Controller
{

    public function showLogin() {
        return view('pages.auth.login', ['action' => route('customer.verify-login')]);
    }

    public function login(Request $request) {
        $login = Auth::guard('customer')->attempt([
            'email_address' => $request->email,
            'password' => $request->password,
        ], $request->get('remember'));
        if ($login) {
            return redirect()->intended();
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function register(Request $request) {

    }
}
