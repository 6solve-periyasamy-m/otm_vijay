<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderInstallment;
use Illuminate\Http\Request;

class OrderInstallmentController extends Controller
{
    public function create(Order $order)
    {
        return view('pages.admin.order.installment.form', ['order' => $order,]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(OrderInstallment::getValidationRules());
        $paymentInstallment = OrderInstallment::make([
            'due_on' => $request->input('due_on'),
            'amount' => $request->input('amount'),
        ]);
        $order->installments()->save($paymentInstallment);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function edit(Order $order, OrderInstallment $orderInstallment)
    {
        return view('pages.admin.order.installment.form', ['order' => $order, 'orderInstallment' => $orderInstallment,]);
    }

    public function update(Request $request, Order $order, OrderInstallment $orderInstallment)
    {
        $request->validate(OrderInstallment::getValidationRules());
        $orderInstallment->update([
            'due_on' => $request->input('due_on'),
            'amount' => $request->input('amount'),
        ]);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function resync(Order $order)
    {
        $order->repository->resetInstallments();
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, OrderInstallment $orderInstallment)
    {
        $orderInstallment->delete();
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
