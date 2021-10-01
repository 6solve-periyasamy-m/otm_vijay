<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        return view('pages.models.payments.table', ['payments' => Payment::all(),]);
    }

    public function create()
    {
        return view('pages.models.payments.create');
    }

    public function store(Request $request)
    {
        $payment = Payment::create([
            'order_id' => $request->input('order_id'),
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
        ]);
        return redirect()->route('payments.view', ['payment' => $payment,]);
    }

    public function view(Payment $payment)
    {
        return view('pages.models.payments.view', ['payment' => $payment,]);
    }

    public function edit(Payment $payment)
    {
        return view('pages.models.payments.update', ['payment' => $payment,]);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update([
            'order_id' => $request->input('order_id'),
            'payment_method_id' => $request->input('payment_method_id'),
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
        ]);
        return redirect()->route('payments.view', ['payment' => $payment,]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.all');
    }
}
