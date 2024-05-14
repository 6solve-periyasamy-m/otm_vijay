<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Authentication\PasswordResetRequest;
use App\Http\Requests\Admin\Authentication\ReceivedResetRequest;
use App\Http\Requests\Admin\Authentication\SendResetRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use Auth;
use Hash;
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
        $user = User::where('email', '=', $request->email)->first();
        if ($user !== null && Hash::check($request->password, $user->password)) {
            if (($user->otp_secret === null) || $user->verifyOneTimeCode($request->otp_code)) {
                Auth::login($user, true);
                if (!$user->isOtm()) {
                    try {
                        Auth::logoutOtherDevices($request->password);
                    } catch (AuthenticationException $e) {
                        Log::error($e);
                    }
                }
                $request->session()->regenerate();
                return redirect()->intended(route('dash'));
            } else {
                if ($request->otp_code === null) {
                    return back()->withErrors(['msg' => 'One Time Code is required.']);
                } else {
                    return back()->withErrors(['msg' => 'The one time code you entered is invalid']);
                }
            }
        }
        return back()->withErrors('Could not authenticate with those credentials')->withInput($request->only('email', 'remember'));
    }

    public function forgot()
    {
        return view('pages.auth.passwords.email');
    }

    public function sendForgotEmail(SendResetRequest $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if ($user === null) {
            return back()->withErrors(['msg' => 'A user with that email address does not exist']);
        }
        $success = $user->repository->requestReset();
        if (!$success) {
            return back()->withErrors(['msg' => 'We failed to send you an email to reset your password, please contact an administrator to reset it for you.']);
        }
        return back()->with(['success' => 'We have emailed you a link to reset your password!']);
    }

    public function getNewPassword(ReceivedResetRequest $request)
    {
        return view('pages.auth.passwords.reset', ['token' => $request->token, 'email' => $request->email,]);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if ($user === null) {
            return back()->withErrors(['msg' => 'A user with that email address does not exist']);
        }
        if (!$user->repository->resetPassword($request->token, $request->password)) {
            return back()->withErrors(['msg' => 'The provided reset token is invalid']);
        }
        return redirect()->route('login')->with(['success' => 'Your password has been reset successfully!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
