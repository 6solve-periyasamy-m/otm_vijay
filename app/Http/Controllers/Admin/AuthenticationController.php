<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Log;

class AuthenticationController extends Controller
{
    public function showLogin()
    {
        if (Auth::user() !== null) {
            return redirect()->route('dash');
        }
        return view('pages.auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            try {
                Auth::logoutOtherDevices($request->password);
            } catch (AuthenticationException $e) {
                Log::error($e);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('dash'));
        }
        return back()->withErrors('Could not authenticate with those credentials')->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
