<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\OrdersCustomer;
use Illuminate\Http\Request;

class OrdersCustomerController extends Controller
{

    public function index()
    {
        return view('pages.models.orders_customers.table', ['ordersCustomers' => OrdersCustomer::all(),]);
    }

    public function create()
    {
        return view('pages.models.orders_customers.create');
    }

    public function store(Request $request)
    {
        $ordersCustomer = OrdersCustomer::create([
            'order_id' => $request->input('order_id'),
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
        ]);
        return redirect()->route('orders-customers.view', ['ordersCustomer' => $ordersCustomer,]);
    }

    public function view(OrdersCustomer $ordersCustomer)
    {
        return view('pages.models.orders_customers.view', ['ordersCustomer' => $ordersCustomer,]);
    }

    public function edit(OrdersCustomer $ordersCustomer)
    {
        return view('pages.models.orders_customers.update', ['ordersCustomer' => $ordersCustomer,]);
    }

    public function update(Request $request, OrdersCustomer $ordersCustomer)
    {
        $ordersCustomer->update([
            'order_id' => $request->input('order_id'),
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
        ]);
        return redirect()->route('orders-customers.view', ['ordersCustomer' => $ordersCustomer,]);
    }

    public function destroy(OrdersCustomer $ordersCustomer)
    {
        $ordersCustomer->delete();
        return redirect()->route('orders-customers.all');
    }
}
