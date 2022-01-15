<?php

namespace App\Http\Controllers;

use App\Http\Gateways\StripeGateway;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function success(Request $request) {
        return StripeGateway::success($request);
    }

    public function cancelled(Request $request) {
        return StripeGateway::cancelled($request);
    }
}
