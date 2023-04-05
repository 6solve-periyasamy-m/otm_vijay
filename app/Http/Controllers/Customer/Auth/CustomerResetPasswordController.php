<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password as PasswordValidation;
use Password;

class CustomerResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/customer/portal';

    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('pages.customer.auth.password.reset', ['token' => $token]);
    }

    protected function broker(){
        return Password::broker('customers');
    }

    protected function guard(){
        return Auth::guard('customer');
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email_address', 'password', 'password_confirmation', 'token'
        );
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email_address' => 'required|email',
            'password' => ['required', 'confirmed', PasswordValidation::defaults()],
        ];
    }
}
