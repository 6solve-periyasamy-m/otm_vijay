<?php

namespace App\Http\Controllers\Models;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\MigrateRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Reporting\ReportRepository;
use App\Repository\RoomingRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        return view('pages.models.orders.table', ['orders' => Order::all(),]);
    }

    public function reminders(int $max = 7, int $min = -1000)
    {
        return view('pages.orders.reminders', ['max' => $max, 'min' => $min, 'orders' => ReportRepository::getRemindersReport($max, $min),]);
    }

    public function create()
    {
        return view('pages.models.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate(Order::getValidationRules());
        $request->validate(['tour_id' => 'required|integer|exists:tours,id',]);
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
        $orderCustomer->repository->addAllIncluded();
        RoomingRepository::assignDefaultRooming($orderCustomer);
        $order->repository->resetInstallments();
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
                $orderCustomer->repository->addAllIncluded();
                RoomingRepository::assignDefaultRooming($orderCustomer);
            }
        }
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order)
    {
        $order->repository->refresh();
        return view('pages.models.orders.view', ['order' => $order,]);
    }

    public function switchTour(Order $order)
    {
        return view('pages.orders.migrate', ['order' => $order,]);
    }

    public function migrate(MigrateRequest $request, Order $order)
    {
        $tour = Tour::find($request->tour_id);
        if (!isset($tour)) abort(404);
        $order->repository->migrate($tour, $request->resetPrices(), $request->resetAdjustments());
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function invoice(Order $order)
    {
        return $order->repository->getInvoiceRepository()->getResponseStream();
    }

    public function atol(Order $order)
    {
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }

    public function occupancy(Order $order)
    {
        return view('pages.occupancy.manager', array_merge(RoomingRepository::exportRoomingData($order), ['order' => $order,]));
    }

    public function edit(Order $order)
    {
        return view('pages.models.orders.update', ['order' => $order,]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(Order::getValidationRules());
        $request->validate(['deposit' => 'required|numeric',]);
        $shouldInvoice = $order->deposit != $request->input('deposit');
        $order->update([
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'deposit' => $request->input('deposit'),
            'invoice_footer' => $request->input('invoice_footer'),
            'booking_fee' => $request->input('booking_fee')
        ]);
        event(new OrderEditedEvent($order, $shouldInvoice));
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
