<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderActivity;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderSystemController extends Controller
{
    public function index() {
        return view('pages.orders.search2', ['orders' => Order::all(),]);
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
