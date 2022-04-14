<?php

namespace App\Http\Gateways;

use Illuminate\Http\Request;

abstract class Gateway
{
    public static abstract function checkout(array $items, string $reference, string $paymentType, int $customerId, ?array $intentionData = null);

    public static function success(Request $request) {
        return view('pages.payments.success');
    }

    public static function cancelled(Request $request) {
        return view('pages.payments.cancelled');
    }
}
