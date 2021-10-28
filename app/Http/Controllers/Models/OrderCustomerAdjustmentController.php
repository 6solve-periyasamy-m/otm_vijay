<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCustomerAdjustment;
use App\Models\OrdersCustomer;
use Illuminate\Http\Request;

class OrderCustomerAdjustmentController extends Controller
{

    public function index(Order $order, OrdersCustomer $orderCustomer)
    {
        return view('pages.models.order_customer_adjustments.table', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustments' => OrderCustomerAdjustment::all(),]);
    }

    public function create(Order $order, OrdersCustomer $orderCustomer)
    {
        return view('pages.models.order_customer_adjustments.create', ['order' => $order, 'orderCustomer' => $orderCustomer, ]);
    }

    public function store(Request $request, Order $order, OrdersCustomer $orderCustomer)
    {
        $request->validate(OrderCustomerAdjustment::getValidationRules());
        $orderCustomerAdjustment = OrderCustomerAdjustment::make([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        $orderCustomer->adjustments()->save($orderCustomerAdjustment);
        return redirect()->route('orders-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer, ]);
    }

    public function view(Order $order, OrdersCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        return view('pages.models.order_customer_adjustments.view', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]);
    }

    public function edit(Order $order, OrdersCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        return view('pages.models.order_customer_adjustments.update', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]);
    }

    public function update(Request $request, Order $order, OrdersCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $request->validate(OrderCustomerAdjustment::getValidationRules());
        $orderCustomerAdjustment->update([
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'date' => $request->input('date'),
        ]);
        return redirect()->route('orders-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer, ]);
    }

    public function destroy(Order $order, OrdersCustomer $orderCustomer, OrderCustomerAdjustment $orderCustomerAdjustment)
    {
        $orderCustomerAdjustment->delete();
        return redirect()->route('orders-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer, ]);
    }
}
