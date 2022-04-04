<?php

namespace App\Http\Controllers\Models;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour;
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
        $tour = Tour::findOrFail($request->input('tour_id'));
        $order = Order::create([
            'tour_id' => $request->input('tour_id'),
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer,
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
        OrderRepository::addIncludedToCustomer($orderCustomer);
        OrderRepository::assignDefaultRooming($orderCustomer);
        OrderRepository::cloneInstallments($order);
        event(new OrderCreatedEvent($order));
        event(new OrderCustomerCreatedEvent($orderCustomer, false));
        if (isset($request->customers)) {
            foreach ($request->customers as $customerId) {
                $orderCustomer = OrderCustomer::make([
                    'customer_id' => $customerId,
                    'tour_cost' => $order->tour->base_price_per_person,
                    'single_occupancy_surcharge' => $order->tour->single_occupancy_surcharge,
                ]);
                $order->orderCustomers()->save($orderCustomer);
                OrderRepository::addIncludedToCustomer($orderCustomer);
                OrderRepository::assignDefaultRooming($orderCustomer);
            }
        }
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order)
    {
        return view('pages.models.orders.view', ['order' => $order,]);
    }

    public function invoice(Order $order)
    {
        return view('pdf.invoices.columns', ['invoice' => OrderRepository::generateInvoice($order),]);
    }

    public function atol(Order $order)
    {
        return OrderRepository::showAtolCertificate($order);
    }

    public function edit(Order $order)
    {
        return view('pages.models.orders.update', ['order' => $order,]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(Order::getValidationRules());
        $request->validate(['deposit' => 'required|numeric',]);
        $order->update([
            'tour_id' => $request->input('tour_id'),
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'deposit' => $request->input('deposit'),
            'invoice_footer' => $request->input('invoice_footer'),
        ]);
        event(new OrderEditedEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order)
    {
        $order->cancelled = true;
        $order->save();
        event(new OrderCancelledEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function restore(Order $order)
    {
        $order->cancelled = false;
        $order->save();
        event(new OrderRestoredEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
