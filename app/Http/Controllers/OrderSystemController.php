<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\TableRequest;
use App\Models\Order\Order;
use Illuminate\Http\Request;

class OrderSystemController extends Controller
{
    public function index(TableRequest $request) {
        return view('pages.orders.search2', ['orders' => Order::all(), 'historic' => $request->historic ?? false,]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Order $order)
    {
        $order->repository->refresh();
        return view('pages.orders.view', ['order' => $order,]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
