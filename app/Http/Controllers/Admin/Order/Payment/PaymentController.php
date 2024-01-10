<?php

namespace App\Http\Controllers\Admin\Order\Payment;

use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Events\Order\Payment\PaymentEditedEvent;
use App\Events\Order\Payment\PaymentRemovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        return view('pages.admin.order.payment.form', ['order' => $order,]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(Payment::getValidationRules());
        $value = $request->input('payment_type') === "Refund" ? abs($request->input('amount')) * -1 : abs($request->input('amount'));
        $payment = Payment::make([
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $value,
            'paid_on' => $request->input('paid_on'),
            'customer_id' => $request->input('customer_id'),
        ]);
        $order->payments()->save($payment);
        event(new PaymentCreatedEvent($payment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function edit(Order $order, Payment $payment)
    {
        return view('pages.admin.order.payment.form', ['order' => $order, 'payment' => $payment,]);
    }

    public function update(Request $request, Order $order, Payment $payment)
    {
        $request->validate(Payment::getValidationRules());
        $value = $request->input('payment_type') === "Refund" ? abs($request->input('amount')) * -1 : abs($request->input('amount'));
        $payment->update([
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $value,
            'paid_on' => $request->input('paid_on'),
            'customer_id' => $request->input('customer_id'),
        ]);
        event(new PaymentEditedEvent($payment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, Payment $payment)
    {
        $payment->delete();
        event(new PaymentRemovedEvent($payment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
