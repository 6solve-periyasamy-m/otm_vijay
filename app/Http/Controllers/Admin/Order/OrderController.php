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
use App\Repository\Model\Order\ItineraryRepository;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Reporting\ReportRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function invoice(Order $order, $version = 0): StreamedResponse
    {
        $invoice =
            $order->invoices()->where('invoice_number', '=', $version)->first()
            ?? $order->repository->getInvoiceRepository()->invoice;
        $invoice->payment_schedule = $order->repository->getScheduleItineraryArray();
        $invoice->organization = $order->organization;
        $invoice->agent = $order->agent;
        return (new InvoiceRepository($invoice))->getResponseStream();
    }

    public function atol(Order $order)
    {
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }

    public function itinerary(Order $order): StreamedResponse
    {
        return (new ItineraryRepository($order))->getResponseStream($order->leadBooker);
    }

    public function reservation(Order $order)
    {
        return dompdf(view('pdf.quotes.itinerary', ['itinerary' => $order->repository->getReservationDocument(), 'type' => 'Reservation']));

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

}
