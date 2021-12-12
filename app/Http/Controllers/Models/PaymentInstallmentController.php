<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\PaymentInstallment;
use App\Models\Tour;
use Illuminate\Http\Request;

class PaymentInstallmentController extends Controller
{

    public function index(Tour $tour)
    {
        return view('pages.models.payment_installments.table', ['tour' => $tour, 'paymentInstallments' => PaymentInstallment::all(),]);
    }

    public function create(Tour $tour)
    {
        return view('pages.models.payment_installments.create', ['tour' => $tour,]);
    }

    public function store(Request $request, Tour $tour)
    {
        $request->validate(PaymentInstallment::getValidationRules());
        $paymentInstallment = PaymentInstallment::make([
            'due_on' => $request->input('due_on'),
            'amount' => $request->input('amount'),
        ]);
        $tour->paymentInstallments()->save($paymentInstallment);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function view(Tour $tour, PaymentInstallment $paymentInstallment)
    {
        return view('pages.models.payment_installments.view', ['tour' => $tour, 'paymentInstallment' => $paymentInstallment,]);
    }

    public function edit(Tour $tour, PaymentInstallment $paymentInstallment)
    {
        return view('pages.models.payment_installments.update', ['tour' => $tour, 'paymentInstallment' => $paymentInstallment,]);
    }

    public function update(Request $request, Tour $tour, PaymentInstallment $paymentInstallment)
    {
        $request->validate(PaymentInstallment::getValidationRules());
        $paymentInstallment->update([
            'due_on' => $request->input('due_on'),
            'amount' => $request->input('amount'),
        ]);
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }

    public function destroy(Tour $tour, PaymentInstallment $paymentInstallment)
    {
        $paymentInstallment->delete();
        return redirect()->route('tours.view', ['tour' => $tour,]);
    }
}
