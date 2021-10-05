<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{

    public function index()
    {
        return view('pages.models.transports.table', ['transports' => Transport::all(),]);
    }

    public function create()
    {
        return view('pages.models.transports.create');
    }

    public function store(Request $request)
    {
        $transport = Transport::create([
            'transport_type_id' => $request->input('transport_type_id'),
            'operator_id' => $request->input('operator_id'),
            'departure_location_id' => $request->input('departure_location_id'),
            'arrival_location_id' => $request->input('arrival_location_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency' => $request->input('currency'),
            'is_domestic' => $request->input('is_domestic') == "on" ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function view(Transport $transport)
    {
        return view('pages.components.transport', ['transport' => $transport,]);
    }

    public function edit(Transport $transport)
    {
        return view('pages.models.transports.update', ['transport' => $transport,]);
    }

    public function update(Request $request, Transport $transport)
    {
        $transport->update([
            'transport_type_id' => $request->input('transport_type_id'),
            'operator_id' => $request->input('operator_id'),
            'departure_location_id' => $request->input('departure_location_id'),
            'arrival_location_id' => $request->input('arrival_location_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'currency' => $request->input('currency'),
            'is_domestic' => $request->input('is_domestic') == "on" ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);
        return redirect()->route('transports.view', ['transport' => $transport,]);
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();
        return redirect()->route('transports.all');
    }
}
