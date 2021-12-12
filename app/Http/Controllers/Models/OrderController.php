<?php

namespace App\Http\Controllers\Models;

use App\Events\OrderCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCustomer;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        return view('pages.models.orders.table', ['orders' => Order::all(),]);
    }

    public function create()
    {
        return view('pages.models.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate(Order::getValidationRules());
        $order = Order::create([
            'quote_id' => $request->input('quote_id'),
            'tour_id' => $request->input('tour_id'),
            'token' => $request->input('token'),
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        $orderCustomer = OrderCustomer::make([
            'customer_id' => $request->input('lead_booker_id'),
            'tour_cost' => $order->tour->base_price_per_person,
            'single_occupancy_surcharge' => $order->tour->single_occupancy_surcharge,
        ]);
        $order->orderCustomers()->save($orderCustomer);
        $order->lead_booker_id = $orderCustomer->id;
        $order->booking_reference = Order::generateBookingReference($order);
        $order->save();
        OrderRepository::addIncludedToCustomer($orderCustomer, $order);
        event(new OrderCreatedEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order)
    {
        return view('pages.models.orders.view', ['order' => $order,]);
    }

    public function edit(Order $order)
    {
        return view('pages.models.orders.update', ['order' => $order,]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(Order::getValidationRules());
        $order->update([
            'quote_id' => $request->input('quote_id'),
            'tour_id' => $request->input('tour_id'),
            'token' => $request->input('token'),
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
        ]);
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.all');
    }
}
