<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingFormLoginController extends Controller
{
    //

    public function loginWithToken($token)
    {

        $customer = Customer::where("login_token", $token)->first();
        if ($customer == null) {
            abort(404);
        }
        Auth::login($customer);
        // invalidate the old login token
        $new_token = Str::random(64);
        $customer->login_token = $new_token;
        $customer->save();
        if ($customer->password == null || $customer->email == null) {
            // They don't have an account with the system. 
            // So redirect the user to somewhere that'll ask if they want to set some credentials
            // If they choose not to, they'll be given another login link to use for next time, and then
            // they'll be redirected to the tour dashboard. They don't need to set up an account if they don't want to
            // THIS IS JUST AN EXAMPLE OF AN IDEA. If we decide to not bother with this system
            // then we can just return the new token to display somewhere for the user to copy again?
            return "No account is set up, so you would be redirected here to be asked if you wanted to create \"easy credentials\" or something (or allow us to email you, not sure yet).<br>New token: " . $new_token;
        }
        return "Already has own account credentials set up. You would be redirected straight to the booking form.<br>New token (not like its much use): " . $new_token;

    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/'); // or wherever it should redirect to.
    }
}
