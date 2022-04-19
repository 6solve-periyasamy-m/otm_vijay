<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\OrderInstallment;
use App\Models\Order;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderInstallmentController extends Controller
{
    public function create(Order $order)
    {
        return view('pages.models.order_installments.create', ['order' => $order,]);
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
        return view('pages.models.order_installments.update', ['order' => $order, 'orderInstallment' => $orderInstallment,]);
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
        foreach ($order->installments as $installment)
        {
            $installment->delete();
        }
        OrderRepository::cloneInstallments($order);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order, OrderInstallment $orderInstallment)
    {
        $orderInstallment->delete();
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
