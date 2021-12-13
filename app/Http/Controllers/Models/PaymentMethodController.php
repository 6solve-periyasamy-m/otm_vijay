<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function index()
    {
        return view('pages.models.payment_methods.table', ['paymentMethods' => PaymentMethod::all(),]);
    }

    public function create()
    {
        return view('pages.models.payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate(PaymentMethod::getValidationRules());
        $paymentMethod = PaymentMethod::create([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('payment-methods.view', ['paymentMethod' => $paymentMethod,]);
    }

    public function view(PaymentMethod $paymentMethod)
    {
        return view('pages.models.payment_methods.view', ['paymentMethod' => $paymentMethod,]);
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('pages.models.payment_methods.update', ['paymentMethod' => $paymentMethod,]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate(PaymentMethod::getValidationRules());
        $paymentMethod->update([
            'name' => $request->input('name'),
        ]);
        return redirect()->route('payment-methods.view', ['paymentMethod' => $paymentMethod,]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('payment-methods.all');
    }
}
