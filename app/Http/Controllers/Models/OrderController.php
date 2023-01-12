<?php

namespace App\Http\Controllers\Models;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\MigrateRequest;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Tour\Tour;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Reporting\ReportRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\ConvertedCustomer;
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
        /** @var Tour $tour */
        $tour = Tour::with(['accommodationInventoryTours', 'activityInventoryTours', 'flightInventoryTours', 'transportInventoryTours',])->where('id', '=', $request->input('tour_id'))->first();
        if ($tour == null) abort(404);
        $data = [
            'ordered_on' => $request->input('ordered_on'),
            'internal_notes' => $request->input('internal_notes'),
            'external_notes' => $request->input('external_notes'),
            'deposit' => $tour->deposit,
            'invoice_footer' => $tour->invoice_footer,
        ];
        $lead = new ConvertedCustomer(Customer::find($request->input('lead_booker_id')));
        $travellers = [];
        if (isset($request->customers)) {
            foreach ($request->customers as $customerId) {
                $travellers[] = new ConvertedCustomer(Customer::find($customerId));
            }
        }
        $order = OrderRepository::create($tour, $data, $lead, $travellers);
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
