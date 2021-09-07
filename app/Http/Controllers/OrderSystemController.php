<?php

namespace App\Http\Controllers;

use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderSystemController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->has('query') && $request->get('query') != null ? $request->get('query') : "";
        $showArchived = $request->has('archived') ? $request->get('archived') : false;
        return view('orders.search', ['data' => OrderRepository::getSearchOrders($searchQuery, $showArchived), 'query' => $searchQuery, 'archived' => $showArchived,]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
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
