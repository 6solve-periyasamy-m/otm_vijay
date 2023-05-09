<?php

namespace App\Http\Controllers\Models;

use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\CreateOrderRequest;
use App\Http\Requests\Admin\Order\MigrateRequest;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Reporting\ReportRepository;

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

    public function store(CreateOrderRequest $request)
    {
        $order = OrderRepository::create($request->getTour(), $request->getData(), $request->getLeadBooker(), $request->getCustomers(), $request->doEmail());
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
        return view('pages.occupancy.manager', ['order' => $order,]);
    }

    public function edit(Order $order)
    {
        return view('pages.models.orders.update', ['order' => $order,]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $shouldInvoice = $order->deposit != $request->deposit;
        $order->update($request->getData());
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
