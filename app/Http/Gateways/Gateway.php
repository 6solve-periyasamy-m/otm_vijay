<?php

namespace App\Http\Gateways;

use App\Models\Order;
use Illuminate\Http\Request;

abstract class Gateway
{
    public static abstract function checkout(array $items, Order $order, string $paymentType, int $customerId, ?array $intentionData = null);

    public static function success(Request $request) {
        return view('pages.payments.success');
    }

    public static function cancelled(Request $request) {
        return view('pages.payments.cancelled');
    }
}
