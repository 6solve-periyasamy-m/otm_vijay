<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrdersCustomer;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrdersCustomerController extends Controller
{

    public function index(Order $order)
    {
        return view('pages.models.orders_customers.table', ['order' => $order, 'ordersCustomers' => OrdersCustomer::all(),]);
    }

    public function create(Order $order)
    {
        return view('pages.models.orders_customers.create', ['order' => $order, ]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(OrdersCustomer::getValidationRules());
        $ordersCustomer = OrdersCustomer::make([
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
        ]);
        $order->orderCustomers()->save($ordersCustomer);
        return redirect()->route('orders-customers.view', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]);
    }

    public function edit(Order $order, OrdersCustomer $ordersCustomer)
    {
        return view('pages.models.orders_customers.update', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]);
    }

    public function update(Request $request, Order $order, OrdersCustomer $ordersCustomer)
    {
        $request->validate(OrdersCustomer::getValidationRules());
        $ordersCustomer->update([
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
        ]);
        return redirect()->route('orders-customers.view', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]);
    }

    public function destroy(Order $order, OrdersCustomer $ordersCustomer)
    {
        $ordersCustomer->delete();
        return redirect()->route('orders.view', ['order' => $order, ]);
    }
}
