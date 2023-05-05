<?php

namespace App\Http\Controllers\Admin\Order;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\Customer\OrderCustomerEditedEvent;
use App\Events\Order\Customer\OrderCustomerRemovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Repository\RoomingRepository;
use Illuminate\Http\Request;

class OrderCustomerModelController extends Controller
{

    public function index(Order $order)
    {
        return view('pages.models.order_customers.table', ['order' => $order, 'orderCustomers' => OrderCustomer::all(),]);
    }

    public function create(Order $order)
    {
        return view('pages.models.order_customers.create', ['order' => $order,]);
    }

    public function store(Request $request, Order $order)
    {
        $request->validate(OrderCustomer::getValidationRules());
        $orderCustomer = OrderCustomer::make([
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'accommodation_notes' => $request->input('accommodation_notes'),
            'activity_notes' => $request->input('activity_notes'),
            'flight_notes' => $request->input('flight_notes'),
            'transport_notes' => $request->input('transport_notes'),
        ]);
        $order->orderCustomers()->save($orderCustomer);
        $orderCustomer->repository->addAllIncluded();;
        RoomingRepository::assignDefaultRooming($orderCustomer);
        event(new OrderCustomerCreatedEvent($orderCustomer));
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function fulfil(Order $order, OrderCustomer $orderCustomer, OrderMerchandise $orderMerchandise)
    {
        $orderMerchandise->repository->update(['fulfilled' => !$orderMerchandise->fulfilled,]);
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function edit(Order $order, OrderCustomer $orderCustomer)
    {
        return view('pages.models.order_customers.update', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function update(Request $request, Order $order, OrderCustomer $orderCustomer)
    {
        $request->validate(OrderCustomer::getValidationRules());
        $orderCustomer->update([
            'customer_id' => $request->input('customer_id'),
            'tour_cost' => $request->input('tour_cost'),
            'single_occupancy_surcharge' => $request->input('single_occupancy_surcharge'),
            'travel_insurer' => $request->input('travel_insurer'),
            'policy_number' => $request->input('policy_number'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'accommodation_notes' => $request->input('accommodation_notes'),
            'activity_notes' => $request->input('activity_notes'),
            'flight_notes' => $request->input('flight_notes'),
            'transport_notes' => $request->input('transport_notes'),
        ]);
        event(new OrderCustomerEditedEvent($orderCustomer));
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function destroy(Order $order, OrderCustomer $orderCustomer)
    {
        foreach ($orderCustomer->groups as $group) {
            if ($group->orderCustomers->count() == 1) { $group->delete(); }
        }
        $orderCustomer->delete();
        event(new OrderCustomerRemovedEvent($orderCustomer));
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
