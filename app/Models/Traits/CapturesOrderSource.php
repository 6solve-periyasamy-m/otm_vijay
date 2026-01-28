<?php

namespace App\Models\Traits;

use App\Models\Order\Order;
use Illuminate\Http\Request;

trait CapturesOrderSource
{
    protected function captureOrderSource(Request $request, Order $order): void 
    {
        $order->confirmed_ip = $request->ip();
        $order->confirmed_user_agent = substr((string) $request->userAgent(), 0, 255);
    }
}
