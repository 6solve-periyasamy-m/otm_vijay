<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCustomer;
use App\Models\OrderCustomerAdjustment;
use Illuminate\Http\Request;

class OrderCustomerAdjustmentController extends Controller
{

    public function index(Order $order, OrderCustomer $orderCustomer)
    {
        return view('pages.models.order_customer_adjustments.table', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustments' => OrderCustomerAdjustment::all(),]);
    }

    public function create(Order $order, OrderCustomer $orderCustomer)
    {
        return view('pages.models.order_customer_adjustments.create', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function store(Request $request, Order $order, OrderCustomer $orderCustomer)
    {
        $request->validate(OrderCustomerAdjustment::getValidationRules());
        $orderCustomerAdjustment = OrderCustomerAdjustment::make([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        $orderCustomer->adjustments()->save($orderCustomerAdjustment);
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function view(Order $order, OrderCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        return view('pages.models.order_customer_adjustments.view', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]);
    }

    public function edit(Order $order, OrderCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        return view('pages.models.order_customer_adjustments.update', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]);
    }

    public function update(Request $request, Order $order, OrderCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $request->validate(OrderCustomerAdjustment::getValidationRules());
        $orderCustomerAdjustment->update([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function destroy(Order $order, OrderCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $orderCustomerAdjustment->delete();
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }
}
