<?php

namespace App\Http\Controllers\Models;

use App\Events\PaymentMadeEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index(Order $order)
    {
        return view('pages.models.payments.table', ['order' => $order, 'payments' => Payment::all(),]);
    }

    public function create(Order $order)
    {
        return view('pages.models.payments.create', ['order' => $order,]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(Payment::getValidationRules());
        $value = $request->input('payment_type') === "Refund" ? abs($request->input('amount')) * -1 : abs($request->input('amount'));
        $payment = Payment::make([
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $value,
            'payment_type' => $request->input('payment_type'),
            'paid_on' => $request->input('paid_on'),
        ]);
        $order->payments()->save($payment);
        event(new PaymentMadeEvent($payment));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order, Payment $payment)
    {
        return view('pages.models.payments.view', ['order' => $order, 'payment' => $payment,]);
    }

    public function edit(Order $order, Payment $payment)
    {
        return view('pages.models.payments.update', ['order' => $order, 'payment' => $payment,]);
    }

    public function update(Request $request, Order $order, Payment $payment)
    {
        $request->validate(Payment::getValidationRules());
        $value = $request->input('payment_type') === "Refund" ? abs($request->input('amount')) * -1 : abs($request->input('amount'));
        $payment->update([
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $value,
            'payment_type' => $request->input('payment_type'),
            'paid_on' => $request->input('paid_on'),
        ]);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, Payment $payment)
    {
        $payment->delete();
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
