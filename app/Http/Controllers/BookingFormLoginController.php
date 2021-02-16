<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingFormLoginController extends Controller
{
    //

    public function login($token)
    {
        $customer = Customer::where("password", $token)->first(); // Obviously this won't stay this way, this is just a proof of concept.
        if ($customer == null) {
            abort(404);
        }
        Auth::login($customer);
        return "done, you are logged in!"; // example for now. 
    }

}
