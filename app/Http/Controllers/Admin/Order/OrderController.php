<?php

namespace App\Http\Controllers\Admin\Order;

use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\CreateOrderRequest;
use App\Http\Requests\Admin\Order\MigrateRequest;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Http\Requests\Admin\TableRequest;
use App\Models\Order\Invoice\Invoice;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use App\Repository\Model\Order\InvoiceRepository;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Reporting\ReportRepository;

class OrderController extends Controller
{

    public function index(TableRequest $request)
    {
        return view('pages.admin.order.table', ['orders' => Order::all(), 'historic' => $request->historic ?? false]);
    }

    public function reminders(int $max = 7, int $min = -1000)
    {
        return view('pages.admin.order.reminders', ['max' => $max, 'min' => $min, 'orders' => ReportRepository::getRemindersReport($max, $min),]);
    }

    public function create()
    {
        return view('pages.admin.order.create');
    }

    public function store(CreateOrderRequest $request)
    {
        $order = OrderRepository::create($request->getTour(), $request->getData(), $request->getLeadBooker(), $request->getCustomers(), $request->doEmail());
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function view(Order $order)
    {
        $order->repository->refresh();
        return view('pages.admin.order.view', ['order' => $order,]);
    }

    public function switchTour(Order $order)
    {
        return view('pages.admin.order.migrate', ['order' => $order,]);
    }

    public function migrate(MigrateRequest $request, Order $order)
    {
        $tour = Tour::find($request->tour_id);
        if (!isset($tour)) return back()->withErrors(['msg' => 'A Tour with that ID does not exist']);
        $order->repository->migrate($tour, $request->resetPrices(), $request->resetAdjustments());
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function latestInvoice(Order $order)
    {
        return $order->repository->getInvoiceRepository()->getResponseStream();
    }

    public function invoice(Order $order, string $version = 'latest')
    {
        $invoice =
            $order->invoices()->where('invoice_number', '=', $version)->first()
            ?? $order->repository->getInvoiceRepository()->invoice;
        return (new InvoiceRepository($invoice))->getResponseStream();
    }

    public function atol(Order $order)
    {
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }

    public function occupancy(Order $order)
    {
        return view('pages.admin.order.occupancy', ['order' => $order,]);
    }

    public function edit(Order $order)
    {
        return view('pages.admin.order.update', ['order' => $order,]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $shouldInvoice = $order->deposit != $request->deposit;
        $order->repository->update($request->getData());
        event(new OrderEditedEvent($order, $shouldInvoice));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function destroy(Order $order)
    {
        $order->repository->update(['cancelled' => true,]);
        event(new OrderCancelledEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function restore(Order $order)
    {
        $order->repository->update(['cancelled' => false,]);
        event(new OrderRestoredEvent($order));
        return redirect()->route('orders.view', ['order' => $order,]);
    }

    public function forceDelete(Order $order)
    {
        if (!is_otm()) abort(403);
        $order->repository->forceDelete();
        return redirect()->route('orders.all');
    }

    public function itineraryInvoice(Order $order, Invoice|null $invoice = null)

    {
        $invoice = $invoice ?? $order->repository->getInvoiceRepository()->invoice;

        $tour = Tour::with(
            'accommodationInventoryTours',
            'accommodationInventoryTours.inventory',
            'accommodationInventoryTours.inventory.roomType',
            'accommodationInventoryTours.inventory.boardType',
            'accommodationInventoryTours.inventory.component',
            'activityInventoryTours',
            'activityInventoryTours.inventory',
            'activityInventoryTours.inventory.ticketType',
            'activityInventoryTours.inventory.component',
            'activityInventoryTours.inventory.component.activityType',
            'flightInventoryTours', 'flightInventoryTours.inventory', 'flightInventoryTours.inventory.component', 'flightInventoryTours.inventory.component.airline', 'flightInventoryTours.inventory.component.departureAirport', 'transportInventoryTours.inventory.component.arrivalAddress',
            'transportInventoryTours', 'transportInventoryTours.inventory', 'transportInventoryTours.inventory.travelClass', 'transportInventoryTours.inventory.component', 'transportInventoryTours.inventory.component.operator', 'transportInventoryTours.inventory.component.departureAddress', 'transportInventoryTours.inventory.component.arrivalAddress',
            'merchandise', 'merchandise.inventory', 'merchandise.inventory.size', 'merchandise.inventory.variant', 'merchandise.inventory.component', 'merchandise.inventory.component.type',
            'paymentInstallments', 'orders', 'orders.leadBooker'
        )->find($order->tour_id);

        return dompdf(view('pdf.invoices.itinerary', ['tour' => $tour, 'order' => $order, 'invoice' => $invoice,]));
    }

}
