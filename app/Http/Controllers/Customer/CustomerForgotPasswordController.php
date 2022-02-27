<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Password;

class CustomerForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function showLinkRequestForm()
    {
        return view('pages.customer.auth.password.email');
    }

    protected function broker(){
        return Password::broker('customers');
    }

    public function guard(){
        return Auth::guard('customer');
    }

    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            ['email_address' => $request->input('email'),]
        );

        return $response == \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
