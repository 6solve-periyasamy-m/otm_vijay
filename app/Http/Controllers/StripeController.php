<?php

namespace App\Http\Controllers;

use App\Http\Gateways\StripeGateway;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->gateway = new StripeGateway();
    }

    public function success(Request $request) {
        return $this->gateway->success($request);
    }

    public function cancelled(Request $request) {
        return $this->gateway->cancelled($request);
    }
}
