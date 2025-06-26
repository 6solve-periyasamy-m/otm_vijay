<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderCustomerRequest;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;

class OrderCustomerController extends Controller
{
    public function create(Order $order)
    {
        return view('pages.admin.order.customer.form', ['order' => $order,]);
    }

    public function show(Order $order, OrderCustomer $orderCustomer) {
        $order->repository->refresh();
        $storedFields = setting('system.customer.fields');
        $selectedFields = !empty($storedFields)
            ? explode(',', $storedFields)
            : default_customer_fields();
        return view('pages.admin.order.customer.view', ['orderCustomer' => $orderCustomer, 'customerFields' => $selectedFields]);
    }

    public function loadTourComponents(Order $order, OrderCustomer $orderCustomer)
    {
        $this->authorize('read', $orderCustomer); // Optional ACL check

        return view('partials.admin.order.customer.tour-components', [
            'orderCustomer' => $orderCustomer,
            'customerViewUrl' => route('order-customers.view', [
                'order' => $order,
                'orderCustomer' => $orderCustomer,
            ]),
        ]);
    }

    public function store(OrderCustomerRequest $request, Order $order)
    {
        $orderCustomer = $order->repository->addCustomer($request->getConvertedCustomer());
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function fulfil(Order $order, OrderCustomer $orderCustomer, OrderMerchandise $orderMerchandise)
    {
        $orderMerchandise->repository->update(['fulfilled' => !$orderMerchandise->fulfilled,]);
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function edit(Order $order, OrderCustomer $orderCustomer)
    {
        return view('pages.admin.order.customer.form', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function update(OrderCustomerRequest $request, Order $order, OrderCustomer $orderCustomer)
    {
        $orderCustomer->repository->update($request->getData());
        return redirect()->route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
    }

    public function destroy(Order $order, OrderCustomer $orderCustomer)
    {
        if ($orderCustomer->id !== $order->lead_booker_id) {
            $orderCustomer->repository->forceDelete();
        }
        return redirect()->route('orders.view', ['order' => $order,]);
    }
}
